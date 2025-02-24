@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Reports</h2>

    <!-- Project Progress Section -->
    <div class="bg-white rounded-lg shadow-lg">
        <div class="p-6">
            <h3 class="text-xl font-bold mb-6">Project Progress</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-[#1e3a8a] text-white">
                            <th class="px-6 py-4 text-center w-16">#</th>
                            <th class="px-6 py-4 text-left">Project</th>
                            <th class="px-6 py-4 text-center w-32">Completed Task</th>
                            <th class="px-6 py-4 text-center w-32">Status</th>
                            <th class="px-6 py-4 text-center w-32">Start Date</th>
                            <th class="px-6 py-4 text-center w-32">End Date</th>
                            <th class="px-6 py-4 text-left">Assigned Member</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($projects as $index => $project)
                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer" onclick="showProjectProgress({{ json_encode($project) }})">
                                <td class="px-6 py-4 text-center">
                                    <span class="text-blue-600 font-medium">{{ $index + 1 }}</span>
                                </td>
                                <td class="px-6 py-4 font-medium">{{ $project['name'] }}</td>
                                <td class="px-6 py-4 text-center">{{ $project['completed_tasks'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center">
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            @if($project['status'] === 'todo') bg-gray-100 text-gray-800
                                            @elseif($project['status'] === 'in_progress') bg-yellow-100 text-yellow-800
                                            @elseif($project['status'] === 'completed') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', str_replace('todo', 'to do', $project['status']))) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">{{ $project['start_date'] }}</td>
                                <td class="px-6 py-4 text-center">{{ $project['end_date'] }}</td>
                                <td class="px-6 py-4">{{ $project['assigned_members'] ?: 'No members assigned' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No projects found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Project Progress Modal -->
<div id="projectProgressModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Project Progress Details</h3>
            <button onclick="closeProjectProgress()" class="text-gray-600 hover:text-gray-800">
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
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700">Start Date</h4>
                        <p id="projectStartDate" class="mt-1 text-gray-600 bg-gray-50 p-3 rounded-md"></p>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700">End Date</h4>
                        <p id="projectEndDate" class="mt-1 text-gray-600 bg-gray-50 p-3 rounded-md"></p>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-700">Status</h4>
                    <p id="projectStatus" class="mt-1"></p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-700">Task Progress</h4>
                    <div id="projectProgress" class="mt-1 text-gray-600 bg-gray-50 p-3 rounded-md"></div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-700">Assigned Members</h4>
                    <p id="projectMembers" class="mt-1 text-gray-600 bg-gray-50 p-3 rounded-md"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles for the reports page */
    .bg-[#1e3a8a] {
        background-color: #1e3a8a;
    }
    
    /* Smooth transitions */
    .transition-colors {
        transition: all 0.2s ease-in-out;
    }
    
    /* Table header styling */
    th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.875rem;
    }
    
    /* Table row hover effect */
    tr:hover td {
        background-color: rgba(59, 130, 246, 0.05);
    }
</style>

@push('scripts')
<script>
    function showProjectProgress(project) {
        document.getElementById('projectName').textContent = project.name;
        document.getElementById('projectStartDate').textContent = project.start_date;
        document.getElementById('projectEndDate').textContent = project.end_date;
        
        // Create status badge
        const statusBadge = document.createElement('span');
        statusBadge.className = `inline-flex items-center justify-center px-3 py-1 text-sm font-medium rounded-full 
            ${project.status === 'todo' ? 'bg-gray-100 text-gray-800' :
            project.status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' :
            project.status === 'completed' ? 'bg-green-100 text-green-800' :
            'bg-red-100 text-red-800'}`;
        statusBadge.textContent = project.status.replace('todo', 'to do').replace('_', ' ').toUpperCase();
        
        const statusContainer = document.getElementById('projectStatus');
        statusContainer.innerHTML = '';
        statusContainer.appendChild(statusBadge);
        
        // Set task progress
        document.getElementById('projectProgress').textContent = `${project.completed_tasks} tasks completed`;
        
        // Set assigned members
        document.getElementById('projectMembers').textContent = project.assigned_members || 'No members assigned';
        
        // Show modal
        document.getElementById('projectProgressModal').classList.remove('hidden');
    }

    function closeProjectProgress() {
        document.getElementById('projectProgressModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('projectProgressModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('projectProgressModal')) {
            closeProjectProgress();
        }
    });

    // Close modal when pressing escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeProjectProgress();
        }
    });
</script>
@endpush
@endsection 