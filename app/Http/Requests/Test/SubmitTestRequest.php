<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

class SubmitTestRequest extends FormRequest
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
            'test_id'   => ['required', 'integer', 'exists:tests,id'],
            'child_id'  => ['required', 'integer', 'exists:children,id'],
            'answers'   => ['required', 'array'],
            'time_left' => ['nullable', 'integer', 'min:0', 'max:3600'],
        ];
    }
}
