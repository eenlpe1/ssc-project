<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Scripts and Styles -->
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
            <!-- Profile Picture -->
            <div class="px-6 pt-7 pb-4">
                <div class="w-20 h-20 mx-auto rounded-full bg-white overflow-hidden flex items-center justify-center border-2 border-yellow-300">
                    @if(Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture" class="w-full h-full object-cover">
                    @else
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    @endif
                </div>
                <div class="mt-3 text-center">
                    <h4 class="text-white font-medium">{{ Auth::user()->name }}</h4>
                    <span class="text-gray-300 text-sm">{{ ucfirst(Auth::user()->role) }}</span>
                </div>
            </div>
            <nav class="mt-4">
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    HOME
                </a>
                <a href="{{ route('projects.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700 {{ request()->routeIs('projects.*') ? 'bg-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    PROJECT
                </a>
                <a href="{{ route('tasks.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700 {{ request()->routeIs('tasks.*') ? 'bg-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    TASK
                </a>
                <a href="{{ route('leaderboard') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700 {{ request()->routeIs('leaderboard') ? 'bg-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    LEADERBOARD
                </a>
                <a href="{{ route('reports') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700 {{ request()->routeIs('reports') ? 'bg-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    REPORT
                </a>
                @if(Auth::user()->role !== 'user')
                <a href="{{ route('users.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700 {{ request()->routeIs('users.*') ? 'bg-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    USER
                </a>
                @endif
                <a href="{{ route('discussions.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700 {{ request()->routeIs('discussions.*') ? 'bg-blue-700' : '' }}">
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

                        <!-- User Role Dropdown -->
                        <div class="relative">
                            <button id="adminDropdown" class="flex items-center space-x-2 focus:outline-none">
                                <div class="flex flex-col items-end mr-1">
                                    <span class="font-semibold text-base leading-tight">{{ Auth::user()->name }}</span>
                                    <span class="text-[11px] leading-tight text-gray-700">{{ ucfirst(Auth::user()->role) }}</span>
                                </div>
                                <svg id="adminArrow" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Admin Dropdown Menu -->
                            <div id="adminMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 11-6 0v-1m6 0H9"></path>
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
            <div class="mt-16">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        // Admin dropdown functionality
        const adminButton = document.getElementById('adminDropdown');
        const adminMenu = document.getElementById('adminMenu');
        const adminArrow = document.getElementById('adminArrow');

        function toggleAdminMenu() {
            adminMenu.classList.toggle('hidden');
            adminArrow.classList.toggle('rotate-180');
        }

        // Toggle admin menu on button click
        adminButton.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleAdminMenu();
            // Close notification menu when opening admin menu
            notificationMenu.classList.add('hidden');
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

        // Simplified notification functionality
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
    @stack('scripts')
</body>
</html> 