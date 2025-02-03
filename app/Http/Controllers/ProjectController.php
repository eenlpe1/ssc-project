<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\ProjectUpdated;
use App\Models\User;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Project::query();

            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $projects = $query->withCount('tasks')
                ->orderBy('id', 'asc')
                ->get();

            return view('projects.index', [
                'projects' => $projects,
                'currentStatus' => $request->status ?? 'all'
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading projects: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $project = Project::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => 'todo'
            ]);

            // Notify all users about the new project
            User::all()->each(function ($user) use ($project) {
                $user->notify(new ProjectUpdated($project, 'created'));
            });

            DB::commit();
            return redirect()->route('projects.index')
                ->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating project: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Project $project)
    {
        // Check if user has access
        if (!auth()->user()->canCreateProjects()) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to edit projects.');
        }

        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $project->update($validated);

            // Notify users assigned to this project's tasks
            $users = $project->tasks()->with('assignedUser')->get()
                ->pluck('assignedUser')
                ->unique('id');

            foreach ($users as $user) {
                if ($user) {
                    $user->notify(new ProjectUpdated($project, 'updated'));
                }
            }

            DB::commit();
            return redirect()->route('projects.index')
                ->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating project: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Project $project)
    {
        try {
            DB::beginTransaction();
            
            $project->delete();
            
            DB::commit();
            return redirect()->route('projects.index')
                ->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting project: ' . $e->getMessage());
        }
    }

    public function show(Project $project)
    {
        return response()->json([
            'id' => $project->id,
            'name' => $project->name,
            'description' => $project->description,
            'start_date' => $project->start_date->format('Y-m-d'),
            'end_date' => $project->end_date->format('Y-m-d'),
            'status' => $project->status,
        ]);
    }

    public function markAsComplete(Project $project)
    {
        try {
            DB::beginTransaction();
            
            \Log::info('Attempting to mark project as complete', ['project_id' => $project->id]);
            
            $project->update(['status' => 'completed']);
            
            // Notify users assigned to project tasks
            $users = $project->tasks->pluck('assigned_to')->unique();
            foreach ($users as $userId) {
                $user = User::find($userId);
                if ($user) {
                    $user->notify(new ProjectUpdated($project, 'completed'));
                }
            }
            
            DB::commit();
            \Log::info('Project marked as complete successfully', ['project_id' => $project->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Project marked as complete successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error completing project: ' . $e->getMessage(), [
                'project_id' => $project->id,
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Error marking project as complete: ' . $e->getMessage()
            ], 500);
        }
    }
} 