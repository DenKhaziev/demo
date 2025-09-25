<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestResource extends JsonResource
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
            'test_type_id' => $this->test_type_id,
            'subject_id' => $this->subject_id,
            'subject' => $this->subject->subject,
            'grade_id' => $this->grade_id,
            'allotted_time' => $this->allotted_time,
            'number_of_attempts' => $this->number_of_attempts,
            'test_type' => $this->type->type,
            'questions' => QuestionResource::collection($this->whenLoaded('questions'))
//            'test_type' => new TestTypeResource($this->whenLoaded('testType')),
//            'tests' =>TestResource::collection($this->tests),
        ];
    }
}
