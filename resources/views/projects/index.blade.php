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
        <h2 class="text-2xl font-bold">PROJECT</h2>
        @if(Auth::user()->canCreateProjects())
            <button id="addProjectBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Project
            </button>
        @endif
    </div>

    <!-- Project Filters -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="flex border-b">
            <a href="{{ route('projects.index') }}" 
               class="px-6 py-3 {{ $currentStatus === 'all' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600' }}">
                All
            </a>
            <a href="{{ route('projects.index', ['status' => 'todo']) }}" 
               class="px-6 py-3 {{ $currentStatus === 'todo' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600' }}">
                To do
            </a>
            <a href="{{ route('projects.index', ['status' => 'overdue']) }}" 
               class="px-6 py-3 {{ $currentStatus === 'overdue' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600' }}">
                Overdue
            </a>
            <a href="{{ route('projects.index', ['status' => 'completed']) }}" 
               class="px-6 py-3 {{ $currentStatus === 'completed' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600' }}">
                Completed
            </a>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full whitespace-nowrap">
            <thead>
                <tr class="bg-[#1e3a8a] text-white">
                    <th class="px-6 py-4 text-center">#</th>
                    <th class="px-6 py-4 text-left">Project</th>
                    <th class="px-6 py-4 text-left">Description</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Number of Task</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @php $projectNumber = 1; @endphp
                @forelse($projects as $project)
                    <tr class="hover:bg-gray-50 transition-colors duration-200 cursor-pointer" onclick="showProjectDetails({{ $project->id }})">
                        <td class="px-6 py-4 text-center text-blue-600 font-medium">{{ $projectNumber++ }}</td>
                        <td class="px-6 py-4 text-blue-600 font-medium">{{ $project->name }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ Str::limit($project->description, 100) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center">
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($project->status === 'todo') bg-gray-100 text-gray-800
                                    @elseif($project->status === 'in_progress') bg-yellow-100 text-yellow-800
                                    @elseif($project->status === 'completed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', str_replace('todo', 'to do', $project->status))) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center text-blue-600 font-medium">{{ $project->task_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No projects found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Project Modal -->
<div id="addProjectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Add New Project</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Project Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
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
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Project</button>
            </div>
        </form>
    </div>
</div>

<!-- Project Details Modal -->
<div id="projectDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Project Details</h3>
            <button onclick="closeProjectDetails()" class="text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="mt-4">
            <div class="space-y-6">
                <div>
                    <h4 class="text-lg font-semibold text-gray-700">Project Name</h4>
                    <p id="projectName" class="mt-1 text-gray-600 bg-gray-50 p-3 rounded-md"></p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-700">Description</h4>
                    <p id="projectDescription" class="mt-1 text-gray-600 bg-gray-50 p-3 rounded-md min-h-[100px] whitespace-pre-wrap"></p>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700">Start Date</h4>
                        <p id="projectStartDate" class="mt-1 text-gray-600 bg-gray-50 p-3 rounded-md"></p>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700">Due Date</h4>
                        <p id="projectDueDate" class="mt-1 text-gray-600 bg-gray-50 p-3 rounded-md"></p>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-700">Status</h4>
                    <p id="projectStatus" class="mt-1"></p>
                </div>
                <div class="flex justify-end mt-6 space-x-4">
                    @if(Auth::user()->canCreateProjects())
                    <button id="editProjectBtn" 
                            onclick="openEditProjectModal()"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-300">
                        Edit Project
                    </button>
                    @endif
                    <button id="completeProjectBtn" 
                            onclick="completeProject(document.querySelector('#projectDetailsModal').dataset.projectId)"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-all duration-300">
                        Mark as Complete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Project Modal -->
<div id="editProjectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Edit Project</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeEditModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="editProjectForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Project Name</label>
                    <input type="text" name="name" id="editProjectName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="editProjectDescription" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" id="editProjectStartDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" id="editProjectEndDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Project</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('addProjectModal');
    const addProjectBtn = document.getElementById('addProjectBtn');

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    addProjectBtn.addEventListener('click', openModal);

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

    function showProjectDetails(projectId) {
        fetch(`/projects/${projectId}`)
            .then(response => response.json())
            .then(project => {
                document.getElementById('projectName').textContent = project.name;
                document.getElementById('projectDescription').textContent = project.description || 'No description provided';
                document.getElementById('projectStartDate').textContent = project.start_date;
                document.getElementById('projectDueDate').textContent = project.end_date;
                
                // Create status badge
                const statusBadge = document.createElement('span');
                statusBadge.className = `inline-flex items-center justify-center px-3 py-1 text-sm font-medium rounded-full 
                    ${project.status === 'todo' ? 'bg-gray-100 text-gray-800' :
                    project.status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' :
                    project.status === 'completed' ? 'bg-green-100 text-green-800' :
                    'bg-red-100 text-red-800'}`;
                statusBadge.textContent = project.status.replace('_', ' ').toUpperCase();
                
                const statusContainer = document.getElementById('projectStatus');
                statusContainer.innerHTML = '';
                statusContainer.appendChild(statusBadge);
                
                // Store project ID for complete button
                document.querySelector('#projectDetailsModal').dataset.projectId = project.id;
                
                // Show/hide complete button based on status
                const completeBtn = document.getElementById('completeProjectBtn');
                if (project.status === 'completed') {
                    completeBtn.classList.add('hidden');
                } else {
                    completeBtn.classList.remove('hidden');
                }
                
                document.getElementById('projectDetailsModal').classList.remove('hidden');
            });
    }

    function closeProjectDetails() {
        document.getElementById('projectDetailsModal').classList.add('hidden');
    }

    function completeProject(projectId) {
        if (!confirm('Are you sure you want to mark this project as complete?')) {
            return;
        }

        fetch(`/projects/${projectId}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close the project details modal first
                closeProjectDetails();
                
                // Create success message element
                const successMessage = document.createElement('div');
                successMessage.className = 'mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative';
                successMessage.setAttribute('role', 'alert');
                successMessage.innerHTML = `<span class="block sm:inline">${data.message}</span>`;
                
                // Insert message at the top of the content area
                const contentArea = document.querySelector('.p-6.mt-20');
                contentArea.insertBefore(successMessage, contentArea.firstChild);
                
                // Remove message and reload after 2 seconds
                setTimeout(() => {
                    successMessage.remove();
                    window.location.reload();
                }, 2000);
            } else {
                // Create error message element
                const errorMessage = document.createElement('div');
                errorMessage.className = 'mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative';
                errorMessage.setAttribute('role', 'alert');
                errorMessage.innerHTML = `<span class="block sm:inline">${data.error || 'Error marking project as complete'}</span>`;
                
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
            errorMessage.innerHTML = '<span class="block sm:inline">Error marking project as complete</span>';
            
            // Insert message at the top of the content area
            const contentArea = document.querySelector('.p-6.mt-20');
            contentArea.insertBefore(errorMessage, contentArea.firstChild);
            
            // Remove error message after 3 seconds
            setTimeout(() => {
                errorMessage.remove();
            }, 3000);
        });
    }

    // Close modal when clicking outside
    document.getElementById('projectDetailsModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('projectDetailsModal')) {
            closeProjectDetails();
        }
    });

    // Close modal when pressing escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeProjectDetails();
        }
    });

    function openEditProjectModal() {
        const projectId = document.querySelector('#projectDetailsModal').dataset.projectId;
        
        // Fetch full project details for editing
        fetch(`/projects/${projectId}`)
            .then(response => response.json())
            .then(project => {
                // Set form action
                document.getElementById('editProjectForm').action = `/projects/${projectId}`;
                
                // Populate form fields
                document.getElementById('editProjectName').value = project.name;
                document.getElementById('editProjectDescription').value = project.description || '';
                document.getElementById('editProjectStartDate').value = project.start_date;
                document.getElementById('editProjectEndDate').value = project.end_date;
                
                // Show edit modal
                document.getElementById('editProjectModal').classList.remove('hidden');
                document.getElementById('editProjectModal').classList.add('flex');
            });
    }

    function closeEditModal() {
        document.getElementById('editProjectModal').classList.add('hidden');
        document.getElementById('editProjectModal').classList.remove('flex');
    }

    // Close edit modal when clicking outside
    document.getElementById('editProjectModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('editProjectModal')) {
            closeEditModal();
        }
    });
</script>
@endpush
@endsection 