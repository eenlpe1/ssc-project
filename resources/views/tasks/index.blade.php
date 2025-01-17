@extends('layouts.app')

@section('content')
<div class="p-6 mt-20">
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
        <button id="addTaskBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Task
        </button>
    </div>

    <!-- Task Filters -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="flex border-b">
            <a href="{{ route('tasks.index') }}" 
               class="px-6 py-3 {{ $currentStatus === 'all' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600' }}">
                All
            </a>
            <a href="{{ route('tasks.index', ['status' => 'todo']) }}" 
               class="px-6 py-3 {{ $currentStatus === 'todo' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600' }}">
                To Do
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
                    <th class="px-6 py-4 text-center">Status</th>
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
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($task->status === 'todo') bg-gray-100 text-gray-800
                                @elseif($task->status === 'in_progress') bg-yellow-100 text-yellow-800
                                @elseif($task->status === 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">{{ $task->end_date->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No tasks found</td>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
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

                <div class="flex justify-end mt-6 space-x-4">
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
                    <button id="completeTaskBtn" 
                            onclick="completeTask(document.querySelector('#taskDetailsModal').dataset.taskId)"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-all duration-300">
                        Mark as Complete
                    </button>
                </div>
            </div>
        </div>
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

    addTaskBtn.addEventListener('click', openModal);

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close modal when pressing escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

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
                
                // Store task ID for complete button
                const modal = document.querySelector('#taskDetailsModal');
                modal.dataset.taskId = task.id;
                
                // Show/hide complete button and rating section based on status
                const completeBtn = document.getElementById('completeTaskBtn');
                const ratingSection = document.getElementById('ratingSection');
                const fileUploadForm = document.getElementById('fileUploadForm');
                
                if (task.status === 'completed') {
                    completeBtn.classList.add('hidden');
                    fileUploadForm.classList.add('hidden');
                    if (!task.rating) {
                        ratingSection.classList.remove('hidden');
                        // Set up rating handlers
                        document.querySelectorAll('.star-rating input').forEach(input => {
                            input.onclick = () => rateTask(task.id, input.value);
                        });
                    } else {
                        ratingSection.classList.add('hidden');
                    }
                } else {
                    completeBtn.classList.remove('hidden');
                    fileUploadForm.classList.remove('hidden');
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
                    fileItem.innerHTML = `
                        <span class="text-sm text-gray-600">${file.file_name}</span>
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

    // Set up file upload form handler
    document.getElementById('fileUploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const taskId = document.querySelector('#taskDetailsModal').dataset.taskId;
        const formData = new FormData(this);

        fetch(`/tasks/${taskId}/upload`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTaskFiles(taskId);
                this.reset();
            } else {
                alert('Error uploading file: ' + data.message);
            }
        });
    });

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
                window.location.reload();
            } else {
                // Create error message element
                const errorMessage = document.createElement('div');
                errorMessage.className = 'mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative';
                errorMessage.setAttribute('role', 'alert');
                errorMessage.innerHTML = `<span class="block sm:inline">${data.error || 'Error marking task as complete'}</span>`;
                
                // Insert message at the top of the content area
                const contentArea = document.querySelector('.p-6.mt-20');
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
            const contentArea = document.querySelector('.p-6.mt-20');
            contentArea.insertBefore(errorMessage, contentArea.firstChild);
            
            // Remove error message after 9 seconds
            setTimeout(() => {
                errorMessage.remove();
            }, 9000);
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
</script>
@endpush
@endsection 