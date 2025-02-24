<?php

namespace App\Notifications;

use App\Models\Discussion;
use App\Models\DiscussionMessage as Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DiscussionMessage extends Notification implements ShouldQueue
{
    use Queueable;

    protected $discussion;
    protected $message;

    public function __construct(Discussion $discussion, Message $message)
    {
        $this->discussion = $discussion;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $senderRole = ucfirst($this->message->user->role);
        return [
            'title' => 'New Message in Agenda',
            'message' => "{$this->message->user->name} ({$senderRole}) sends a message in {$this->discussion->title}",
            'discussion_id' => $this->discussion->id,
            'message_id' => $this->message->id,
            'sender_name' => $this->message->user->name,
            'sender_role' => $senderRole,
            'discussion_title' => $this->discussion->title
        ];
    }
} 