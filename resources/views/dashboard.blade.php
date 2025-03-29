@extends('layouts.app')

@section('content')
<div class="p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold">HOME</h2>
                    <p class="text-lg">Welcome {{ Auth::user()->name }}!</p>
                </div>

                <!-- Stats Bar -->
                <div class="bg-[#1e3a8a] text-white rounded-lg p-4 sm:p-6 mb-6">
                    <div class="flex flex-col sm:flex-row justify-between items-center">
                        <select id="statsPeriod" class="bg-transparent border border-white/30 rounded px-3 py-1 cursor-pointer hover:bg-white/10 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white/50 mb-4 sm:mb-0">
                            <option value="weekly" class="bg-white text-gray-800">Weekly</option>
                            <option value="monthly" class="bg-white text-gray-800">Monthly</option>
                            <option value="yearly" class="bg-white text-gray-800">Yearly</option>
                        </select>
                        <div class="flex space-x-8 sm:space-x-16">
                            <div class="text-center">
                                <div id="totalTasks" class="text-3xl sm:text-4xl font-bold">{{ $totalTasks }}</div>
                                <div>Total Tasks</div>
                            </div>
                            <div class="text-center">
                                <div id="totalProjects" class="text-3xl sm:text-4xl font-bold">{{ $totalProjects }}</div>
                                <div>Total Projects</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Table and Ranking -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Project Table -->
                    <div class="col-span-1 lg:col-span-8 bg-white rounded-lg shadow">
                        <div class="p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                                <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-0">Projects Overview</h3>
                                <a href="{{ route('projects.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                    View
                                </a>
                            </div>
                            <div class="overflow-x-auto -mx-4 sm:mx-0">
                                <table class="w-full whitespace-nowrap">
                                    <thead>
                                        <tr class="bg-[#1e3a8a] text-white">
                                            <th class="px-4 sm:px-6 py-3 text-center">#</th>
                                            <th class="px-4 sm:px-6 py-3 text-left">Project</th>
                                            <th class="px-4 sm:px-6 py-3 text-center">Tasks</th>
                                            <th class="px-4 sm:px-6 py-3 text-center">Status</th>
                                            <th class="px-4 sm:px-6 py-3 text-center">Start Date</th>
                                            <th class="px-4 sm:px-6 py-3 text-center">End Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @php $projectRank = 1; @endphp
                                        @forelse($projects as $project)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-4 sm:px-6 py-3 text-center">{{ $projectRank++ }}</td>
                                                <td class="px-4 sm:px-6 py-3 font-medium">{{ $project->name }}</td>
                                                <td class="px-4 sm:px-6 py-3 text-center">{{ $project->tasks_count }}</td>
                                                <td class="px-4 sm:px-6 py-3">
                                                    <div class="flex justify-center">
                                                        <span class="inline-flex items-center justify-center px-3 py-1 text-xs sm:text-sm font-medium rounded-full 
                                                            @if($project->status === 'todo') bg-gray-100 text-gray-800
                                                            @elseif($project->status === 'in_progress') bg-yellow-100 text-yellow-800
                                                            @elseif($project->status === 'completed') bg-green-100 text-green-800
                                                            @else bg-red-100 text-red-800
                                                            @endif">
                                                            {{ ucfirst(str_replace('_', ' ', str_replace('todo', 'to do', $project->status))) }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-4 sm:px-6 py-3 text-center text-xs sm:text-sm">{{ $project->start_date ? $project->start_date->format('M d, Y') : 'N/A' }}</td>
                                                <td class="px-4 sm:px-6 py-3 text-center text-xs sm:text-sm">{{ $project->end_date ? $project->end_date->format('M d, Y') : 'N/A' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-4 sm:px-6 py-4 text-center text-gray-500">No projects found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Rankings Table -->
                    <div class="col-span-1 lg:col-span-4 bg-white rounded-lg shadow-lg p-4 sm:p-6">
                        <h2 class="text-xl font-bold mb-4">Rankings</h2>
                        <div class="overflow-x-auto -mx-4 sm:mx-0">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-[#1e3a8a] text-white">
                                        <th class="px-3 sm:px-6 py-2 sm:py-3 text-center">Rank</th>
                                        <th class="px-3 sm:px-6 py-2 sm:py-3 text-left">Name</th>
                                        <th class="px-3 sm:px-6 py-2 sm:py-3 text-center">Stars</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-center">Tasks</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-center">Projects</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @php $rank = 1; @endphp
                                    @forelse($users as $user)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3 sm:px-6 py-3 text-center">{{ $rank++ }}</td>
                                            <td class="px-3 sm:px-6 py-3 text-xs sm:text-sm">{{ $user['name'] }}</td>
                                            <td class="px-3 sm:px-6 py-3 text-center">
                                                <span class="text-yellow-500">★</span> {{ $user['stars'] }}
                                            </td>
                                            <td class="px-2 sm:px-3 py-3 text-center text-xs sm:text-sm">{{ $user['task_count'] }}</td>
                                            <td class="px-2 sm:px-3 py-3 text-center text-xs sm:text-sm">{{ $user['project_count'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-3 sm:px-6 py-4 text-center text-gray-500">No users found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script>
    document.getElementById('statsPeriod').addEventListener('change', function() {
        const period = this.value;
        // Add visual feedback for the change
        this.classList.add('bg-white/10');
        setTimeout(() => this.classList.remove('bg-white/10'), 200);
        
        // Here we would typically make an AJAX call to get updated stats
        // For now, we'll just show a loading state
        document.getElementById('totalTasks').innerHTML = '<span class="opacity-50 text-2xl">Loading...</span>';
        document.getElementById('totalProjects').innerHTML = '<span class="opacity-50 text-2xl">Loading...</span>';
        
        // Simulate loading new data
        setTimeout(() => {
            document.getElementById('totalTasks').textContent = '{{ $totalTasks }}';
            document.getElementById('totalProjects').textContent = '{{ $totalProjects }}';
        }, 500);
    });
    </script>
@endpush
@endsection