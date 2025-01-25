<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all users with their task and project counts
        $users = User::where('role', 'user')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'stars' => $user->stars,
                    'task_count' => $user->task_count,
                    'project_count' => $user->project_count,
                ];
            })
            ->sortByDesc('stars')
            ->take(15); // Get top 15 users

        // Get project statistics
        $projects = Project::withCount('tasks')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // Get total counts
        $totalTasks = Task::count();
        $totalProjects = Project::count();

        return view('dashboard', compact('users', 'projects', 'totalTasks', 'totalProjects'));
    }
} 