<?php

namespace App\Events;

use App\Http\Resources\MessageBroadcastResource;
use App\Http\Resources\TicketBroadcastResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TicketMessageAdded implements ShouldBroadcastNow
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('notifications');
    }

    public function broadcastAs()
    {
        return 'TicketMessageAdded';
    }

    public function broadcastWith(): array
    {
        $ticket = $this->message->ticket()->with('user')->first();

        return [
            'ticket' => new TicketBroadcastResource($ticket),
            'message' => new MessageBroadcastResource($this->message),
        ];
    }
}
