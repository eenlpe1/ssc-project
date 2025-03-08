<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TaskFeedback extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;
    protected $comment;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task, string $comment = null, string $status = 'pending')
    {
        $this->task = $task;
        $this->comment = $comment;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $statusText = match ($this->status) {
            'for_revision' => 'needs revision',
            'approved' => 'has been approved',
            default => 'is pending review'
        };

        return [
            'title' => 'Task Feedback',
            'message' => "You have received feedback on task '{$this->task->name}'. The task {$statusText}.",
            'task_id' => $this->task->id,
            'project_name' => $this->task->project->name,
            'comment' => $this->comment,
            'status' => $this->status
        ];
    }
}
