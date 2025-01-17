<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Task Completed',
            'message' => "Task '{$this->task->name}' has been marked as complete",
            'task_id' => $this->task->id,
            'project_name' => $this->task->project->name,
            'completion_date' => now()->format('M d, Y')
        ];
    }
}
