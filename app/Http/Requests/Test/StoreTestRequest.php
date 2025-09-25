<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestRequest extends FormRequest
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
            'test_type_id' => 'required|exists:test_types,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade_id' => 'required|exists:grades,id',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.has_image' => 'boolean',
            'questions.*.image_path' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.answer' => 'required|string',
            'questions.*.answers.*.is_correct' => 'boolean',
        ];
    }
}
