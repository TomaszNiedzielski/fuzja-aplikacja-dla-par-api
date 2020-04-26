<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message_id;
    public $message_created_at;
    public $message_from;
    public $message;
    public $partner_id;
    public $user_name;
    public $image;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message_id, $message_created_at, $message_from, $message, $partner_id, $user_name, $image)
    {
        $this->message_id = $message_id;
        $this->message_created_at = $message_created_at;
        $this->message_from = $message_from;
        $this->message = $message;
        $this->partner_id = $partner_id;
        $this->user_name = $user_name;
        $this->image = $image;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chat.'.$this->partner_id);
    }
    public function broadcastWith()
    {
        
        return [
            '_id' => $this->message_id,
            'createdAt' => $this->message_created_at,
            'from' => $this->message_from,
            'text' => $this->message,
            'user' => (object)[
                '_id' => $this->message_from,
                'name' => $this->user_name
            ],
            'image' => $this->image
        ];
    }
    public function broadcastAs()
    {
        return 'NewMessageEvent';
    }
}
