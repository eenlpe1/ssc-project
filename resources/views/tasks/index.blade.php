@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">TASK</h2>
        @if(Auth::user()->canCreateTasks())
            <button id="addTaskBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Task
            </button>
        @endif
    </div>

    <!-- Task Filters -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="flex border-b">
            <a href="{{ route('tasks.index', ['status' => 'todo']) }}" 
               class="px-6 py-3 {{ $currentStatus === 'todo' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600' }}">
                To do
            </a>
            <a href="{{ route('tasks.index', ['status' => 'in_progress']) }}" 
               class="px-6 py-3 {{ $currentStatus === 'in_progress' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600' }}">
                In Progress
            </a>
            <a href="{{ route('tasks.index', ['status' => 'overdue']) }}" 
               class="px-6 py-3 {{ $currentStatus === 'overdue' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600' }}">
                Overdue
            </a>
            <a href="{{ route('tasks.index', ['status' => 'completed']) }}" 
               class="px-6 py-3 {{ $currentStatus === 'completed' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600' }}">
                Completed
            </a>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full whitespace-nowrap">
            <thead>
                <tr class="bg-[#1e3a8a] text-white">
                    <th class="px-6 py-4 text-center">#</th>
                    <th class="px-6 py-4 text-left">Task</th>
                    <th class="px-6 py-4 text-left">Description</th>
                    <th class="px-6 py-4 text-left">Project</th>
                    <th class="px-6 py-4 text-left">Assigned To</th>
                    <th class="px-6 py-4 text-center">Due Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @php $taskNumber = 1; @endphp
                @forelse($tasks as $task)
                    <tr class="hover:bg-gray-50 transition-colors duration-200 cursor-pointer" onclick="showTaskDetails({{ $task->id }})">
                        <td class="px-6 py-4 text-center">{{ $taskNumber++ }}</td>
                        <td class="px-6 py-4 text-blue-600 font-medium">{{ $task->name }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ Str::limit($task->description, 50) }}
                        </td>
                        <td class="px-6 py-4">{{ $task->project->name }}</td>
                        <td class="px-6 py-4">{{ $task->assignedUser->name }}</td>
                        <td class="px-6 py-4 text-center">{{ $task->end_date->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No tasks found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Task Modal -->
<div id="addTaskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Add New Task</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Task Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Project</label>
                    <select name="project_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Assigned To</label>
                    <select name="assigned_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50" onclick="closeModal()">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Task</button>
            </div>
        </form>
    </div>
</div>

<!-- Task Details Modal -->
<div id="taskDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" data-task-id="">
    <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Task Details</h3>
            <button onclick="closeTaskDetails()" class="text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="mt-4">
            <div class="space-y-6">
                <div>
                    <h4 class="text-lg font-semibold text-gray-700">Task Name</h4>
                    <p id="taskName" class="mt-1 text-gray-600 bg-gray-50 p-3 rounded-md"></p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-700">Description</h4>
                    <p id="taskDescription" class="mt-1 text-gray-600 bg-gray-50 p-3 rounded-md min-h-[100px] whitespace-pre-wrap"></p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-700">Project</h4>
                    <p id="taskProject" class="mt-1 text-gray-600 bg-gray-50 p-3 rounded-md"></p>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700">Assigned To</h4>
                        <p id="taskAssignedTo" class="mt-1 text-gray-600 bg-gray-50 p-3 rounded-md"></p>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700">Due Date</h4>
                        <p id="taskDueDate" class="mt-1 text-gray-600 bg-gray-50 p-3 rounded-md"></p>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-700">Status</h4>
                    <p id="taskStatus" class="mt-1"></p>
                </div>
                
                <!-- Adviser Feedback Section (visible to all, but only editable by Admin/Adviser) -->
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-semibold text-gray-700">Adviser Feedback</h4>
                    
                    <!-- Read-only view for all users -->
                    <div class="space-y-4 mt-3" id="adviserFeedbackReadOnly">
                        <div>
                            <h5 class="text-sm font-medium text-gray-700 mb-1">Comment</h5>
                            <div id="adviserCommentReadOnly" class="rounded-md border border-gray-300 bg-gray-50 p-3 min-h-[80px] whitespace-pre-wrap text-gray-600">
                                No comment provided yet.
                            </div>
                        </div>
                        <div>
                            <h5 class="text-sm font-medium text-gray-700 mb-1">Status</h5>
                            <div id="adviserStatusBadge" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                Pending
                            </div>
                        </div>
                    </div>
                    
                    @if(Auth::user()->isAdmin() || Auth::user()->isAdviser())
                    <!-- Editable view for Admins and Advisers only -->
                    <div class="space-y-4 mt-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                            <textarea id="adviserComment" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="adviserStatus" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="pending">Pending</option>
                                <option value="for_revision">For Revision</option>
                                <option value="approved">Approved</option>
                            </select>
                        </div>
                        <div class="flex justify-end">
                            <button id="saveAdviserFeedbackBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-300">
                                Save Feedback
                            </button>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- File Upload Section -->
                <div id="fileUploadSection" class="border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-semibold text-gray-700 mb-4">Task Files</h4>
                    
                    <!-- File Upload Form -->
                    <form id="fileUploadForm" class="mb-4 hidden">
                        @csrf
                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                <input type="file" name="task_file" 
                                       class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm
                                              file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                                              file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                                              hover:file:bg-blue-100"
                                       accept=".pdf,.doc,.docx,.zip,.rar"
                                       required>
                            </div>
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-300 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Upload
                            </button>
                        </div>
                    </form>

                    <!-- Files List -->
                    <div id="filesList" class="space-y-2">
                        <!-- Files will be populated here -->
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="border-t border-gray-200 pt-6 flex justify-end space-x-4">
                    <div id="ratingSection" class="hidden">
                        <div class="star-rating flex items-center space-x-1">
                            <input type="radio" id="star5" name="rating" value="5" class="hidden" />
                            <label for="star5" class="text-2xl cursor-pointer text-yellow-400 hover:text-yellow-500">★</label>
                            <input type="radio" id="star4" name="rating" value="4" class="hidden" />
                            <label for="star4" class="text-2xl cursor-pointer text-yellow-400 hover:text-yellow-500">★</label>
                            <input type="radio" id="star3" name="rating" value="3" class="hidden" />
                            <label for="star3" class="text-2xl cursor-pointer text-yellow-400 hover:text-yellow-500">★</label>
                            <input type="radio" id="star2" name="rating" value="2" class="hidden" />
                            <label for="star2" class="text-2xl cursor-pointer text-yellow-400 hover:text-yellow-500">★</label>
                            <input type="radio" id="star1" name="rating" value="1" class="hidden" />
                            <label for="star1" class="text-2xl cursor-pointer text-yellow-400 hover:text-yellow-500">★</label>
                        </div>
                    </div>
                    @if(Auth::user()->canCreateTasks())
                    <button id="editTaskBtn" 
                            onclick="openEditTaskModal()"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-300">
                        Edit Task
                    </button>
                    @endif
                    <button id="completeTaskBtn" 
                            onclick="completeTask(document.querySelector('#taskDetailsModal').dataset.taskId)"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-all duration-300 hidden">
                        Mark as Complete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div id="editTaskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Edit Task</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeEditModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="editTaskForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Task Name</label>
                    <input type="text" name="name" id="editTaskName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="editTaskDescription" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Project</label>
                    <select name="project_id" id="editTaskProject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Assigned To</label>
                    <select name="assigned_to" id="editTaskAssignedTo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" id="editTaskStartDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" id="editTaskEndDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="editTaskStatus" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="todo">To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Task</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('addTaskModal');
    const addTaskBtn = document.getElementById('addTaskBtn');

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Only add event listener if the button exists
    if (addTaskBtn) {
        addTaskBtn.addEventListener('click', openModal);
    }

    // Close modal when clicking outside
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });
    }

    // Show modal if there are validation errors
    @if($errors->any())
        openModal();
    @endif

    function showTaskDetails(taskId) {
        fetch(`/tasks/${taskId}`)
            .then(response => response.json())
            .then(task => {
                document.getElementById('taskName').textContent = task.name;
                document.getElementById('taskDescription').textContent = task.description || 'No description provided';
                document.getElementById('taskProject').textContent = task.project_name;
                document.getElementById('taskAssignedTo').textContent = task.assigned_to;
                document.getElementById('taskDueDate').textContent = task.due_date;
                
                // Create status badge
                const statusBadge = document.createElement('span');
                statusBadge.className = `inline-flex items-center justify-center px-3 py-1 text-sm font-medium rounded-full 
                    ${task.status === 'todo' ? 'bg-gray-100 text-gray-800' :
                    task.status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' :
                    task.status === 'completed' ? 'bg-green-100 text-green-800' :
                    'bg-red-100 text-red-800'}`;
                statusBadge.textContent = task.status.replace('_', ' ').toUpperCase();
                
                const statusContainer = document.getElementById('taskStatus');
                statusContainer.innerHTML = '';
                statusContainer.appendChild(statusBadge);
                
                // Load adviser feedback
                loadAdviserFeedback(task);
                
                // Store task ID for complete button
                const modal = document.querySelector('#taskDetailsModal');
                modal.dataset.taskId = task.id;
                
                // Show/hide complete button and rating section based on status
                const completeBtn = document.getElementById('completeTaskBtn');
                const ratingSection = document.getElementById('ratingSection');
                const fileUploadForm = document.getElementById('fileUploadForm');
                const currentUserId = document.body.dataset.userId;
                
                // console.log('Current User ID:', currentUserId, typeof currentUserId);
                // console.log('Task Assigned To ID:', task.assigned_to_id, typeof task.assigned_to_id);
                // console.log('Is Admin:', isAdmin());
                // console.log('Is Adviser:', isAdviser());
                
                if (task.status === 'completed') {
                    completeBtn.classList.add('hidden');
                    fileUploadForm.classList.add('hidden');
                    if (!task.rating && canRateTasks()) {
                        ratingSection.classList.remove('hidden');
                        // Set up rating handlers
                        document.querySelectorAll('.star-rating input').forEach(input => {
                            input.onclick = () => rateTask(task.id, input.value);
                        });
                    } else {
                        ratingSection.classList.add('hidden');
                    }
                } else {
                    // Show complete button only to admin, adviser, or the assigned user
                    const canComplete = isAdmin() || isAdviser() || String(currentUserId) === String(task.assigned_to_id);
                    if (canComplete) {
                        completeBtn.classList.remove('hidden');
                    } else {
                        completeBtn.classList.add('hidden');
                    }
                    
                    // Show file upload form if user is admin, adviser, or assigned to the task
                    const canUpload = isAdmin() || isAdviser() || String(currentUserId) === String(task.assigned_to_id);
                    // console.log('Can Upload:', canUpload);
                    
                    if (canUpload) {
                        fileUploadForm.classList.remove('hidden');
                    } else {
                        fileUploadForm.classList.add('hidden');
                    }
                    ratingSection.classList.add('hidden');
                }

                // Load task files
                loadTaskFiles(task.id);
                
                modal.classList.remove('hidden');
            });
    }

    function loadTaskFiles(taskId) {
        fetch(`/tasks/${taskId}/files`)
            .then(response => response.json())
            .then(files => {
                const filesList = document.getElementById('filesList');
                filesList.innerHTML = '';

                if (files.length === 0) {
                    const taskStatus = document.querySelector('#taskStatus span').textContent;
                    const message = taskStatus === 'COMPLETED' ? 'No files uploaded.' : 'No files uploaded yet.';
                    filesList.innerHTML = `<p class="text-gray-500 text-sm">${message}</p>`;
                    return;
                }

                files.forEach(file => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'flex items-center justify-between bg-gray-50 p-3 rounded-lg';
                    
                    // Format the date
                    const uploadDate = new Date(file.created_at);
                    const formattedDate = uploadDate.toLocaleString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    fileItem.innerHTML = `
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-600">${file.file_name}</span>
                            <span class="text-xs text-gray-500">Uploaded on ${formattedDate}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="downloadFile(${file.id})" 
                                    class="text-blue-600 hover:text-blue-700 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </button>
                            ${isAdmin() ? `
                                <button onclick="deleteFile(${file.id})" 
                                        class="text-red-600 hover:text-red-700 transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            ` : ''}
                        </div>
                    `;
                    filesList.appendChild(fileItem);
                });
            });
    }

    function isAdmin() {
        return document.body.dataset.userRole === 'admin';
    }

    function isAdviser() {
        const userRole = document.body.dataset.userRole;
        return userRole === 'adviser';
    }

    function canRateTasks() {
        const userRole = document.body.dataset.userRole;
        return userRole === 'admin' || userRole === 'adviser';
    }

    // Set up file upload form handler
    const fileUploadForm = document.getElementById('fileUploadForm');
    if (fileUploadForm) {
        fileUploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const taskId = document.querySelector('#taskDetailsModal').dataset.taskId;
            const formData = new FormData();
            formData.append('task_file', this.querySelector('input[name="task_file"]').files[0]);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            fetch(`/tasks/${taskId}/upload`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadTaskFiles(taskId);
                    this.reset();
                } else {
                    alert('Error uploading file: ' + (data.message || 'Unknown error occurred'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error uploading file. Please try again.');
            });
        });
    }

    function downloadFile(fileId) {
        fetch(`/tasks/files/${fileId}/download`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(data => {
                    alert(data.message);
                });
            } else {
                return response.blob().then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = response.headers.get('content-disposition')?.split('filename=')[1] || 'download';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    a.remove();
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error downloading file. Please try again.');
        });
    }

    function deleteFile(fileId) {
        if (!confirm('Are you sure you want to delete this file?')) {
            return;
        }

        fetch(`/tasks/files/${fileId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const taskId = document.querySelector('#taskDetailsModal').dataset.taskId;
                loadTaskFiles(taskId);
            } else {
                alert('Error deleting file: ' + data.message);
            }
        });
    }

    // Add user role to body for admin check
    document.body.dataset.userRole = '{{ Auth::user()->role }}';
    document.body.dataset.userId = '{{ Auth::id() }}';

    function closeTaskDetails() {
        document.getElementById('taskDetailsModal').classList.add('hidden');
    }

    function completeTask(taskId) {
        if (!confirm('Are you sure you want to mark this task as complete?')) {
            return;
        }

        fetch(`/tasks/${taskId}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close the task details modal first
                closeTaskDetails();
                
                // Create success message element
                const successMessage = document.createElement('div');
                successMessage.className = 'mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative';
                successMessage.setAttribute('role', 'alert');
                successMessage.innerHTML = `<span class="block sm:inline">${data.message}</span>`;
                
                // Insert message at the top of the content area
                const contentArea = document.querySelector('.p-6');
                contentArea.insertBefore(successMessage, contentArea.firstChild);
                
                // Add a delay before reloading to ensure notification is processed
                setTimeout(() => {
                window.location.reload();
                }, 1000);
            } else {
                // Create error message element
                const errorMessage = document.createElement('div');
                errorMessage.className = 'mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative';
                errorMessage.setAttribute('role', 'alert');
                errorMessage.innerHTML = `<span class="block sm:inline">${data.error || 'Error marking task as complete'}</span>`;
                
                // Insert message at the top of the content area
                const contentArea = document.querySelector('.p-6');
                contentArea.insertBefore(errorMessage, contentArea.firstChild);
                
                // Remove error message after 3 seconds
                setTimeout(() => {
                    errorMessage.remove();
                }, 3000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Create error message element
            const errorMessage = document.createElement('div');
            errorMessage.className = 'mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative';
            errorMessage.setAttribute('role', 'alert');
            errorMessage.innerHTML = '<span class="block sm:inline">Error marking task as complete</span>';
            
            // Insert message at the top of the content area
            const contentArea = document.querySelector('.p-6');
            contentArea.insertBefore(errorMessage, contentArea.firstChild);
            
            // Remove error message after 3 seconds
            setTimeout(() => {
                errorMessage.remove();
            }, 3000);
        });
    }

    function rateTask(taskId, rating) {
        fetch(`/tasks/${taskId}/rate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ rating: rating })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }

    // Close modal when clicking outside
    document.getElementById('taskDetailsModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('taskDetailsModal')) {
            closeTaskDetails();
        }
    });

    // Close modal when pressing escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeTaskDetails();
        }
    });

    function openEditTaskModal() {
        const taskId = document.querySelector('#taskDetailsModal').dataset.taskId;
        
        // Fetch full task details for editing
        fetch(`/tasks/${taskId}`)
            .then(response => response.json())
            .then(task => {
                // Set form action
                document.getElementById('editTaskForm').action = `/tasks/${taskId}`;
                
                // Populate form fields
                document.getElementById('editTaskName').value = task.name;
                document.getElementById('editTaskDescription').value = task.description || '';
                document.getElementById('editTaskProject').value = task.project_id;
                document.getElementById('editTaskAssignedTo').value = task.assigned_to_id;
                document.getElementById('editTaskStartDate').value = task.start_date;
                document.getElementById('editTaskEndDate').value = task.end_date;
                document.getElementById('editTaskStatus').value = task.status;
                
                // Show edit modal
                document.getElementById('editTaskModal').classList.remove('hidden');
                document.getElementById('editTaskModal').classList.add('flex');
            });
    }

    function closeEditModal() {
        document.getElementById('editTaskModal').classList.add('hidden');
        document.getElementById('editTaskModal').classList.remove('flex');
    }

    // Close edit modal when clicking outside
    document.getElementById('editTaskModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('editTaskModal')) {
            closeEditModal();
        }
    });

    // Adviser Feedback Functions
    if (document.getElementById('saveAdviserFeedbackBtn')) {
        document.getElementById('saveAdviserFeedbackBtn').addEventListener('click', function() {
            const taskId = document.querySelector('#taskDetailsModal').dataset.taskId;
            const comment = document.getElementById('adviserComment').value;
            const adviserStatus = document.getElementById('adviserStatus').value;
            
            saveAdviserFeedback(taskId, comment, adviserStatus);
        });
    }

    function saveAdviserFeedback(taskId, comment, adviserStatus) {
        fetch(`/tasks/${taskId}/adviser-feedback`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ comment, adviser_status: adviserStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Feedback saved successfully', 'success');
            } else {
                showNotification('Error: ' + data.error, 'error');
            }
        })
        .catch(error => {
            console.error('Error saving feedback:', error);
            showNotification('An error occurred while saving feedback', 'error');
        });
    }

    function loadAdviserFeedback(task) {
        // Update the read-only view for all users
        const commentEl = document.getElementById('adviserCommentReadOnly');
        if (commentEl) {
            commentEl.textContent = task.comment || 'No comment provided yet.';
        }
        
        const statusBadgeEl = document.getElementById('adviserStatusBadge');
        if (statusBadgeEl) {
            let statusText = 'Pending';
            let statusClass = 'bg-gray-100 text-gray-800';
            
            if (task.adviser_status === 'for_revision') {
                statusText = 'For Revision';
                statusClass = 'bg-yellow-100 text-yellow-800';
            } else if (task.adviser_status === 'approved') {
                statusText = 'Approved';
                statusClass = 'bg-green-100 text-green-800';
            }
            
            statusBadgeEl.textContent = statusText;
            statusBadgeEl.className = `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${statusClass}`;
        }
        
        // Update the editable fields for admin/adviser
        if (document.getElementById('adviserComment')) {
            document.getElementById('adviserComment').value = task.comment || '';
        }
        
        if (document.getElementById('adviserStatus')) {
            document.getElementById('adviserStatus').value = task.adviser_status || 'pending';
        }
    }

    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
            'bg-red-100 text-red-800 border border-red-200'
        } z-50`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => notification.remove(), 500);
        }, 3000);
    }
</script>
@endpush
@endsection 