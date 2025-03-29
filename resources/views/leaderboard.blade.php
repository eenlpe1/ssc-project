@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6">
    <h2 class="text-2xl font-bold mb-6">LEADERBOARD</h2>

    <!-- Top User Card -->
    @if($topUser)
    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-8 mb-6 sm:mb-8">
        <h3 class="text-lg sm:text-xl font-bold mb-4 sm:mb-6">RANK 1</h3>
        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-8">
            <!-- Profile Section -->
            <div class="flex flex-col items-center mb-4 sm:mb-0">
                <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-lg mb-3 overflow-hidden">
                    @if($topUser->profile_picture)
                        <img src="{{ asset('storage/' . $topUser->profile_picture) }}" alt="{{ $topUser->name }}'s Profile Picture" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                <h4 class="font-semibold text-base sm:text-lg mb-2">{{ $topUser->name }}</h4>
                <div class="bg-yellow-100 rounded-full px-3 py-1">
                    <span class="text-sm font-medium text-yellow-800">Top Performer</span>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 w-full">
                <!-- Stars -->
                <div class="flex flex-col items-center justify-center bg-gray-50 rounded-lg p-3 sm:p-4">
                    <div class="flex items-center gap-2 mb-1 sm:mb-2">
                        <span class="text-3xl sm:text-4xl font-bold">{{ $topUser->total_stars }}</span>
                        <span class="text-2xl sm:text-3xl text-yellow-400">★</span>
                    </div>
                    <span class="text-sm sm:text-base text-gray-600">Stars</span>
                </div>

                <!-- Completed Tasks -->
                <div class="flex flex-col items-center justify-center bg-gray-50 rounded-lg p-3 sm:p-4">
                    <div class="text-3xl sm:text-4xl font-bold text-blue-600 mb-1 sm:mb-2">
                        {{ $topUser->tasks()->where('status', 'completed')->count() }}
                    </div>
                    <span class="text-sm sm:text-base text-gray-600">Completed Tasks</span>
                </div>

                <!-- Completed Projects -->
                <div class="flex flex-col items-center justify-center bg-gray-50 rounded-lg p-3 sm:p-4">
                    <div class="text-3xl sm:text-4xl font-bold text-blue-600 mb-1 sm:mb-2">
                        {{ $topUser->getProjectCountAttribute() }}
                    </div>
                    <span class="text-sm sm:text-base text-gray-600">Completed Projects</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Rankings Section -->
    <div class="bg-white rounded-lg shadow-lg">
        <div class="p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold mb-4 sm:mb-6">Ranking</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-[#1e3a8a] text-white">
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center w-16 sm:w-24">Rank</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left">Name</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center w-20 sm:w-32">Stars</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center hidden sm:table-cell w-40">Complete Task</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center hidden md:table-cell w-40">Completed Project</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">
                                    <span class="text-blue-600 font-medium">{{ $user['rank'] }}</span>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm sm:text-base">{{ $user['name'] }}</td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">
                                    <span class="text-yellow-400">★</span>
                                    <span class="ml-1 text-sm sm:text-base">{{ $user['stars'] }}</span>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-center hidden sm:table-cell text-sm sm:text-base">{{ $user['completed_tasks'] }}</td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-center hidden md:table-cell text-sm sm:text-base">{{ $user['completed_projects'] }}</td>
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

<style>
    /* Custom styles for the leaderboard */
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