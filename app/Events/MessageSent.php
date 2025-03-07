<?php
// app/Events/MessageSent.php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('conversation.' . $this->conversation->id);  // public channel
        // Or use a private channel
        // return new Channel('private-conversation.' . $this->conversation->id);
    }


    public function broadcastAs()
    {
        return 'MessageSent'; // Ensure this matches the event name you're listening for in JavaScript
    }
}
