<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateBroadcastResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'grade_id' => $this->grade_id,
            'child_id' => $this->child_id,
            'is_has_a_certificate' => $this->is_has_a_certificate,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'child' => [
                'id' => $this->child->id,
                'name' => $this->child->name,
            ],
            'user' => [
                'id' => $this->child->user->id,
                'name' => $this->child->user->name,
            ],
        ];
    }
}
