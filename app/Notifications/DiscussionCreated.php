<?php

namespace App\Notifications;

use App\Models\Discussion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DiscussionCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $discussion;

    public function __construct(Discussion $discussion)
    {
        $this->discussion = $discussion;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Agenda Created',
            'message' => "A new agenda '{$this->discussion->title}' has been created",
            'discussion_id' => $this->discussion->id,
            'location' => $this->discussion->location,
            'date' => $this->discussion->date->format('M d, Y')
        ];
    }
}
