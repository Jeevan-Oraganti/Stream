<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NoticeAlert extends Notification
{
    use Queueable;

    private $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return []; // No delivery channels since we are not sending it anywhere
    }

    /**
     * Get the notification data for display.
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
        ];
    }
}
