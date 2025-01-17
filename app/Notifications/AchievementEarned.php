<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AchievementEarned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $achievement;
    protected $stars;

    public function __construct(string $achievement, int $stars)
    {
        $this->achievement = $achievement;
        $this->stars = $stars;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Achievement Earned!',
            'message' => "Congratulations! You've earned {$this->stars} stars for {$this->achievement}",
            'achievement' => $this->achievement,
            'stars' => $this->stars,
            'total_stars' => $notifiable->total_stars
        ];
    }
}
