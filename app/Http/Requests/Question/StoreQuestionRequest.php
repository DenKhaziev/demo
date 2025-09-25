<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'questions' => 'required|array',
            'questions.*.question' => 'required|string',
            'questions.*.has_image' => 'nullable|boolean',
            'questions.*.image_path' => 'nullable|string',
            'questions.*.answers' => 'required|array|min:1',
            'questions.*.answers.*.answer' => 'required|string',
            'questions.*.answers.*.is_correct' => 'boolean',
        ];
    }
}
