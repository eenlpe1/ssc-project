<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/forgot-password', [LoginController::class, 'showForgotPassword'])->name('password.forgot');
    Route::post('/forgot-password/admin', [LoginController::class, 'adminPasswordResetRequest'])->name('password.admin.request');
    Route::post('/forgot-password/admin/verify', [LoginController::class, 'verifyAdminReset'])->name('password.admin.verify');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Project routes
    Route::resource('projects', ProjectController::class);

    // Task routes
    Route::resource('tasks', TaskController::class);

    // User routes
    Route::resource('users', UserController::class);
    Route::put('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

    // Other routes
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');

    // Discussion routes
    Route::get('/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
    Route::post('/discussions', [DiscussionController::class, 'store'])->name('discussions.store');
    Route::get('/discussions/{discussion}', [DiscussionController::class, 'show'])->name('discussions.show');
    Route::post('/discussions/{discussion}/messages', [DiscussionController::class, 'storeMessage'])->name('discussions.messages.store');
    Route::post('/discussions/{discussion}/end', [DiscussionController::class, 'endDiscussion'])->name('discussions.end');
    Route::delete('/discussions/{discussion}', [DiscussionController::class, 'destroy'])->name('discussions.destroy');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/projects/{project}/complete', [ProjectController::class, 'markAsComplete'])->name('projects.complete');

    Route::post('/tasks/{task}/complete', [TaskController::class, 'markAsComplete'])->name('tasks.complete');

    Route::post('/tasks/{task}/rate', [TaskController::class, 'rateTask'])->name('tasks.rate');

    Route::post('/tasks/{task}/adviser-feedback', [TaskController::class, 'updateAdviserFeedback'])
        ->name('tasks.adviser-feedback');

    // Task file routes
    Route::get('/tasks/{task}/files', [TaskController::class, 'getFiles'])->name('tasks.files');
    Route::post('/tasks/{task}/upload', [TaskController::class, 'uploadFile'])->name('tasks.upload');
    Route::get('/tasks/files/{file}/download', [TaskController::class, 'downloadFile'])->name('tasks.download');
    Route::delete('/tasks/files/{file}', [TaskController::class, 'deleteFile'])->name('tasks.files.delete');

    // Notification Routes
    Route::middleware('auth')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');
    });
});
