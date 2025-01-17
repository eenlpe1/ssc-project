<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskRated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;
    protected $rating;
    protected $stars;

    public function __construct(Task $task, int $rating, int $stars)
    {
        $this->task = $task;
        $this->rating = $rating;
        $this->stars = $stars;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Task Rated',
            'message' => "Task '{$this->task->name}' has been rated {$this->rating} stars. You earned {$this->stars} stars!",
            'task_id' => $this->task->id,
            'project_name' => $this->task->project->name,
            'rating' => $this->rating,
            'stars_earned' => $this->stars
        ];
    }
}
