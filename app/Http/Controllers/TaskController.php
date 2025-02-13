<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\TaskFile;
use App\Notifications\TaskAssigned;
use App\Notifications\AchievementEarned;
use App\Notifications\TaskCompleted;
use App\Notifications\TaskRated;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Task::with(['project', 'assignedUser']);

            // Update statuses before filtering
            Task::where('status', '!=', 'completed')->get()->each->updateStatusBasedOnDates();

            // Filter by status if provided
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            $tasks = $query->orderBy('created_at', 'desc')->get();
            $projects = Project::orderBy('name')->get();
            $users = User::orderBy('name')->get();

            return view('tasks.index', [
                'tasks' => $tasks,
                'projects' => $projects,
                'users' => $users,
                'currentStatus' => $request->status ?? 'all'
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading tasks: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'project_id' => 'required|exists:projects,id',
                'assigned_to' => 'required|exists:users,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $task = Task::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'project_id' => $validated['project_id'],
                'assigned_to' => $validated['assigned_to'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => 'todo'
            ]);

            // Send notification to assigned user
            $assignedUser = User::find($validated['assigned_to']);
            \Log::info('Sending task notification', [
                'task_id' => $task->id,
                'user_id' => $assignedUser->id,
                'user_name' => $assignedUser->name
            ]);
            
            $assignedUser->notify(new TaskAssigned($task));
            
            \Log::info('Task notification sent', [
                'task_id' => $task->id,
                'user_id' => $assignedUser->id,
                'notification_count' => $assignedUser->unreadNotifications()->count()
            ]);

            DB::commit();
            
            // Return with a flag to refresh notifications
            return redirect()->route('tasks.index')
                ->with('success', 'Task created successfully.')
                ->with('refresh_notifications', true);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating task: ' . $e->getMessage(), [
                'exception' => $e,
                'validated_data' => $validated ?? null
            ]);
            return back()->with('error', 'Error creating task: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Task $task)
    {
        // Check if user has access
        if (!auth()->user()->canCreateTasks()) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to edit tasks.');
        }

        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'project_id' => 'required|exists:projects,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'assigned_to' => 'required|exists:users,id',
                'status' => 'required|in:todo,in_progress,completed,overdue',
            ]);

            $task->update($validated);

            // If the task is marked as completed through the edit form
            if ($validated['status'] === 'completed' && $task->wasChanged('status')) {
                // Notify the assigned user
                $task->assignedUser->notify(new TaskCompleted($task));
            }

            DB::commit();
            return redirect()->route('tasks.index')
                ->with('success', 'Task updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating task: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Task $task)
    {
        try {
            DB::beginTransaction();
            
            $task->delete();
            
            DB::commit();
            return redirect()->route('tasks.index')
                ->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting task: ' . $e->getMessage());
        }
    }

    public function show(Task $task)
    {
        return response()->json([
            'id' => $task->id,
            'name' => $task->name,
            'description' => $task->description,
            'project_id' => $task->project_id,
            'project_name' => $task->project->name,
            'assigned_to_id' => (string)$task->assigned_to,
            'assigned_to' => $task->assignedUser->name,
            'start_date' => $task->start_date->format('Y-m-d'),
            'end_date' => $task->end_date->format('Y-m-d'),
            'due_date' => $task->end_date->format('M d, Y'),
            'status' => $task->status,
            'rating' => $task->rating,
        ]);
    }

    public function getFiles(Task $task)
    {
        $files = $task->files()
            ->select('id', 'file_name', 'uploaded_by')
            ->with('uploader:id,name')
            ->get();

        return response()->json($files);
    }

    public function uploadFile(Request $request, Task $task)
    {
        try {
            DB::beginTransaction();

            // Check if user has permission to upload files
            if (!auth()->user()->isAdmin() && !auth()->user()->isAdviser() && 
                $task->assigned_to !== auth()->id()) {
                throw new \Exception('You do not have permission to upload files to this task. Only administrators, advisers, and the assigned user can upload files.');
            }

            // More detailed validation with increased file size limit
            $request->validate([
                'task_file' => [
                    'required',
                    'file',
                    'max:20480', // Increased to 20MB
                    function ($attribute, $value, $fail) {
                        if (!$value->isValid()) {
                            $fail('The file upload failed. Please try again.');
                        }

                        $mimeType = $value->getMimeType();
                        $allowedTypes = [
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/zip',
                            'application/x-rar-compressed',
                            'application/x-rar',
                            'application/octet-stream'
                        ];

                        if (!in_array($mimeType, $allowedTypes)) {
                            \Log::error('File upload failed - Invalid mime type: ' . $mimeType);
                            $fail('The file must be a PDF, DOC, DOCX, ZIP, or RAR file.');
                        }
                    }
                ]
            ]);

            $file = $request->file('task_file');
            
            // Log file information for debugging
            \Log::info('File upload attempt', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize()
            ]);

            // Handle large files with chunked upload
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('task-files', uniqid() . '_' . $fileName, 'public');

            if (!$filePath) {
                throw new \Exception('Failed to store the file. Please try again.');
            }

            $task->files()->create([
                'file_name' => $fileName,
                'file_path' => $filePath,
                'uploaded_by' => auth()->id()
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('File upload validation error', [
                'errors' => $e->errors(),
                'file' => $request->hasFile('task_file') ? [
                    'name' => $request->file('task_file')->getClientOriginalName(),
                    'size' => $request->file('task_file')->getSize()
                ] : 'No file'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . collect($e->errors())->first()[0]
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('File upload error: ' . $e->getMessage(), [
                'file' => $request->hasFile('task_file') ? [
                    'name' => $request->file('task_file')->getClientOriginalName(),
                    'size' => $request->file('task_file')->getSize()
                ] : 'No file',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error uploading file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadFile($fileId)
    {
        try {
            $file = TaskFile::findOrFail($fileId);
            
            // Check if user has permission to download
            if (!auth()->user()->isAdmin() && !auth()->user()->isAdviser() && 
                $file->task->assigned_to !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to download this file. Only administrators, advisers, and the assigned user can download task files.'
                ], 403);
            }

            return Storage::disk('public')->download($file->file_path, $file->file_name);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error downloading file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteFile($fileId)
    {
        try {
            DB::beginTransaction();

            $file = TaskFile::findOrFail($fileId);

            // Only admin can delete files
            if (!auth()->user()->isAdmin()) {
                throw new \Exception('Only administrators can delete files.');
            }

            // Delete the physical file
            Storage::disk('public')->delete($file->file_path);

            // Delete the database record
            $file->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAsComplete(Task $task)
    {
        try {
            \Log::info('Starting task completion', [
                'task_id' => $task->id,
                'current_status' => $task->status,
                'user_id' => auth()->id()
            ]);

            DB::beginTransaction();

            $task->update(['status' => 'completed']);

            \Log::info('Task status updated', [
                'task_id' => $task->id,
                'new_status' => $task->fresh()->status
            ]);

            // Send notification to assigned user
            $task->assignedUser->notify(new TaskCompleted($task));

            DB::commit();
            
            // Flash success message to session
            session()->flash('success', 'Task marked as complete successfully. Please rate the task to earn stars.');

            return response()->json([
                'success' => true,
                'message' => 'Task marked as complete successfully. Please rate the task to earn stars.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error completing task: ' . $e->getMessage(), [
                'task_id' => $task->id,
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Error marking task as complete: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rateTask(Request $request, Task $task)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5'
            ]);

            $task->update(['rating' => $validated['rating']]);

            // Award stars based on rating
            $user = $task->assignedUser;
            $stars = $validated['rating'];
            $user->increment('total_stars', $stars);

            // Send task rating notification
            $user->notify(new TaskRated($task, $validated['rating'], $stars));

            // Send achievement notification for high ratings (4 or 5 stars)
            if ($validated['rating'] >= 4) {
                $user->notify(new AchievementEarned('High Task Rating', $stars));
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Task rated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => 'Error rating task.'], 500);
        }
    }
} 