<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssigned extends Notification
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
            'title' => 'New Task Assigned',
            'message' => "Task '{$this->task->name}' has been assigned to you",
            'task_id' => $this->task->id,
            'project_name' => $this->task->project->name,
            'due_date' => $this->task->end_date->format('M d, Y')
        ];
    }
}
