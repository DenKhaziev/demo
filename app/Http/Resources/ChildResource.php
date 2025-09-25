<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChildResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'grade_id' =>$this->grade_id,
            'attestation_type' => $this->attestation_type,
            'is_payment' =>$this->is_payment,
            'is_has_a_certificate' =>$this->is_has_a_certificate,
            'doc_birth' => $this->doc_birth,
            'doc_application' => $this->doc_application,
            "doc_POPD" => $this->doc_POPD,
            'tests' => TestResource::collection($this->whenLoaded('tests')),
            'passed_tests' => PassedTestResource::collection($this->whenLoaded('passedTests')),
        ];
    }
}
