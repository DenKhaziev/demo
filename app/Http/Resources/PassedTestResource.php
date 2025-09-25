<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PassedTestResource extends JsonResource
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
            'child_id' => $this->child_id,
            'test_id' => $this->test_id,
            'subject_id' => $this->subject_id,
            'grade_id' => $this->grade_id,
            'test_type_id' => $this->test_type_id,
//            'answers' => $this->answers,
            'score' => $this->score,
            'attempts_left' => $this->attempts_left,
            'status' => $this->status,
            'total_questions' => $this->total_questions
        ];
    }
}
