<?php

namespace App\Http\Requests\TicketMessage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
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
    public function rules()
    {
        $isAdmin = auth()->user()->is_admin;

        return [
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:10000',
            'user_id' => [
                Rule::requiredIf($isAdmin),
                Rule::prohibitedIf(!$isAdmin),
                'integer',
                'exists:users,id',
            ],
        ];
    }
}
