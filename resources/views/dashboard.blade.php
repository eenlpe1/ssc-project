<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - SSC Project Management Tool</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-[#1e3a8a] text-white fixed h-full">
            <!-- Yellow Header -->
            <div class="bg-yellow-300 h-16 p-4">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/phcp-logo.png') }}" alt="Mama Mary" class="h-10">
                    <img src="{{ asset('images/ssc-logo.png') }}" alt="SSC Logo" class="h-10">
                </div>
            </div>
            <nav class="mt-8">
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    HOME
                </a>
                <a href="{{ route('projects.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    PROJECT
                </a>
                <a href="{{ route('tasks.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    TASK
                </a>
                <a href="{{ route('leaderboard') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    LEADERBOARD
                </a>
                <a href="{{ route('reports') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    REPORT
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    USER
                </a>
                <a href="{{ route('discussions.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                    </svg>
                    DISCUSSION BOARD
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Top Navigation -->
            <div class="bg-yellow-300 h-16 p-4 fixed w-[calc(100%-16rem)] z-10">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">SSC PROJECT MANAGEMENT TOOL</h1>
                    <div class="flex items-center space-x-4">
                        <!-- Notification Button -->
                        <div class="relative">
                            <a href="{{ route('notifications.index') }}" class="text-gray-800 hover:text-gray-600 focus:outline-none">
                                <div class="relative">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    <span id="notificationCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center hidden">0</span>
                                </div>
                            </a>
                        </div>

                        <!-- Administrator Dropdown -->
                        <div class="relative">
                            <button id="adminDropdown" class="flex items-center space-x-2 focus:outline-none">
                                <span class="font-semibold">Administrator</span>
                                <svg class="w-4 h-4 transition-transform" id="adminArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="adminMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile
                                </a>
                                <hr class="my-1 border-gray-200">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="p-6 mt-20">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold">HOME</h2>
                    <p class="text-lg">Welcome {{ Auth::user()->name }}!</p>
                </div>

                <!-- Stats Bar -->
                <div class="bg-[#1e3a8a] text-white rounded-lg p-6 mb-6">
                    <div class="flex justify-between items-center">
                        <select id="statsPeriod" class="bg-transparent border border-white/30 rounded px-3 py-1 cursor-pointer hover:bg-white/10 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white/50">
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                        <div class="flex space-x-16">
                            <div class="text-center">
                                <div id="totalTasks" class="text-4xl font-bold">{{ $totalTasks }}</div>
                                <div>Total Tasks</div>
                            </div>
                            <div class="text-center">
                                <div id="totalProjects" class="text-4xl font-bold">{{ $totalProjects }}</div>
                                <div>Total Projects</div>
                            </div>
                        </div>
                    </div>
                </div>

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

                <!-- Project Table and Ranking -->
                <div class="grid grid-cols-12 gap-6">
                    <!-- Project Table -->
                    <div class="col-span-8 bg-white rounded-lg shadow">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-2xl font-bold">Projects Overview</h3>
                                <a href="{{ route('projects.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                    View
                                </a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full whitespace-nowrap">
                                    <thead>
                                        <tr class="bg-[#1e3a8a] text-white">
                                            <th class="px-6 py-4 text-center">#</th>
                                            <th class="px-6 py-4 text-left">Project</th>
                                            <th class="px-6 py-4 text-center">Number of Task</th>
                                            <th class="px-6 py-4 text-center">Status</th>
                                            <th class="px-6 py-4 text-center">Start Date</th>
                                            <th class="px-6 py-4 text-center">End Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @php $projectRank = 1; @endphp
                                        @forelse($projects as $project)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-6 py-4 text-center">{{ $projectRank++ }}</td>
                                                <td class="px-6 py-4 font-medium">{{ $project->name }}</td>
                                                <td class="px-6 py-4 text-center">{{ $project->tasks_count }}</td>
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex items-center justify-center px-3 py-1 text-sm font-medium rounded-full 
                                                        @if($project->status === 'todo') bg-gray-100 text-gray-800
                                                        @elseif($project->status === 'in_progress') bg-yellow-100 text-yellow-800
                                                        @elseif($project->status === 'completed') bg-green-100 text-green-800
                                                        @else bg-red-100 text-red-800
                                                        @endif">
                                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center">{{ $project->start_date ? $project->start_date->format('M d, Y') : 'N/A' }}</td>
                                                <td class="px-6 py-4 text-center">{{ $project->end_date ? $project->end_date->format('M d, Y') : 'N/A' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No projects found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Rankings Table -->
                    <div class="col-span-4 bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold mb-4">Rankings</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-[#1e3a8a] text-white">
                                        <th class="px-6 py-3 text-center">Rank</th>
                                        <th class="px-6 py-3 text-left">Name</th>
                                        <th class="px-6 py-3 text-center">Stars</th>
                                        <th class="px-6 py-3 text-center">Tasks</th>
                                        <th class="px-6 py-3 text-center">Projects</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @php $rank = 1; @endphp
                                    @forelse($users as $user)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-center">{{ $rank++ }}</td>
                                            <td class="px-6 py-4">{{ $user['name'] }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="text-yellow-500">★</span> {{ $user['stars'] }}
                                            </td>
                                            <td class="px-6 py-4 text-center">{{ $user['task_count'] }}</td>
                                            <td class="px-6 py-4 text-center">{{ $user['project_count'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No users found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Admin dropdown functionality
        const adminButton = document.getElementById('adminDropdown');
        const adminMenu = document.getElementById('adminMenu');
        const adminArrow = document.getElementById('adminArrow');
        
        // Toggle admin menu on button click
        adminButton.addEventListener('click', (e) => {
            e.stopPropagation();
            adminMenu.classList.toggle('hidden');
            adminArrow.classList.toggle('rotate-180');
        });
        
        // Close admin menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!adminButton.contains(e.target)) {
                adminMenu.classList.add('hidden');
                adminArrow.classList.remove('rotate-180');
            }
        });
        
        // Close admin menu when pressing escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                adminMenu.classList.add('hidden');
                adminArrow.classList.remove('rotate-180');
            }
        });

        // Notification functionality
        async function updateNotificationCount() {
            try {
                const response = await fetch('/notifications/unread-count', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                const notificationCount = document.getElementById('notificationCount');
                
                if (data.count > 0) {
                    notificationCount.textContent = data.count;
                    notificationCount.classList.remove('hidden');
                } else {
                    notificationCount.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error fetching notification count:', error);
            }
        }

        // Update count on page load
        updateNotificationCount();

        // Check for refresh_notifications flag in session
        @if(session('refresh_notifications'))
            // Force an immediate update of notifications
            updateNotificationCount();
        @endif

        // Poll for new notifications every 30 seconds
        setInterval(updateNotificationCount, 30000);
    </script>
</body>
</html>