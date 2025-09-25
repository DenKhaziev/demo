<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageBroadcastResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'body'       => $this->body,
            'ticket_id'  => $this->ticket_id,
            'user_id'    => $this->user_id,
            'is_admin'   => $this->is_admin,
            'created_at' => $this->created_at,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'last_message' => [
                'body' => $this->body,
                'user_id' => $this->user_id,
                'ticket_id' => $this->ticket_id,
                'created_at' => $this->created_at,
            ],
        ];
    }
}
