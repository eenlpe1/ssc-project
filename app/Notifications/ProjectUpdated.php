<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $project;
    protected $action;

    public function __construct(Project $project, string $action = 'updated')
    {
        $this->project = $project;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $title = $this->action === 'created' ? 'New Project Created' : 'Project Updated';
        $message = $this->action === 'created' 
            ? "New project '{$this->project->name}' has been created"
            : "Project '{$this->project->name}' has been updated";

        return [
            'title' => $title,
            'message' => $message,
            'project_id' => $this->project->id,
            'project_name' => $this->project->name,
            'status' => $this->project->status
        ];
    }
}
