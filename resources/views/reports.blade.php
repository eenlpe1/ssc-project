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
                            <tr class="hover:bg-gray-50 transition-colors">
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
@endsection 