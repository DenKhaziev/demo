<?php

namespace App\Events;

use App\Http\Resources\MessageBroadcastResource;
use App\Http\Resources\TicketBroadcastResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CertificateRequest implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $certificate;
    public function __construct($certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('notifications');
    }
    public function broadcastAs()
    {
        return 'NewCertificateRequest';
    }

    public function broadcastWith(): array
    {
        return [
            'certificate' => $this->certificate,
        ];
    }
}
