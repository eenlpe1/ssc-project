<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Get top user for the main display
        $topUser = User::where('role', 'user')
            ->orderByDesc('total_stars')
            ->first();

        // Get all users for the ranking table
        $users = User::where('role', 'user')
            ->orderByDesc('total_stars')
            ->get()
            ->map(function ($user, $index) {
                return [
                    'rank' => $index + 1,
                    'name' => $user->name,
                    'stars' => $user->total_stars,
                    'completed_tasks' => $user->tasks()->where('status', 'completed')->count(),
                    'completed_projects' => $user->getProjectCountAttribute()
                ];
            });

        return view('leaderboard', compact('topUser', 'users'));
    }
} 