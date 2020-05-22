<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $body;
    public $user_name;

    public function __construct($message)
    {
        $this->body = $message->text;
        $this->user_name = $message->user->name;
    }

    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }

    public function toExpoPush($notifiable)
    {
        return ExpoMessage::create()
            ->badge(1)
            ->enableSound()
            ->title($this->user_name)
            ->setChannelId('chat-messages')
            //->ttl(60)
            ->body($this->body);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}