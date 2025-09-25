<?php

namespace App\Events;

use App\Http\Resources\TicketBroadcastResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;


class TicketCreated implements ShouldBroadcastNow
{
    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function broadcastOn()
    {
        return new Channel('notifications');
    }

    public function broadcastAs()
    {
        return 'TicketCreated';
    }

    public function broadcastWith()
    {
        return [
            'ticket' => new TicketBroadcastResource($this->ticket),
        ];
    }
}
