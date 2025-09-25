<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketBroadcastResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,

            ],
            'last_message' => $this->latestMessage ? [
                'body' => $this->latestMessage->body,
                'user_id' => $this->latestMessage->user_id,
                'ticket_id' => $this->latestMessage->ticket_id,
                'created_at' => $this->latestMessage->created_at,
            ] : null,
        ];
    }
}
