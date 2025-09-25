<?php

namespace App\Http\Requests\PersonalInfo;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StorePersonalInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'child_birth_date' => $this->convertDate($this->child_birth_date),
            'child_birth_cert_issued_at' => $this->convertDate($this->child_birth_cert_issued_at),
            'parent_birth_date' => $this->convertDate($this->parent_birth_date),
            'parent_passport_issued_at' => $this->convertDate($this->parent_passport_issued_at),
            'parent_name'                 => $this->parent_name !== null ? trim($this->parent_name) : null,
        ]);
    }

    private function convertDate($date)
    {
        try {
            return Carbon::createFromFormat('d.m.Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            return $date;
        }
    }

    public function rules(): array
    {
        return [
            // Данные ребёнка
            'child_id' => ['required', 'integer', 'exists:children,id'],
            'child_birth_date' => ['required', 'date'],
            'child_birth_cert_number' => ['required', 'string', 'max:50'],
            'child_birth_cert_issued_at' => ['required', 'date'],
            'child_birth_cert_issued_by' => ['required', 'string', 'max:255'],

            // Данные родителя
            'parent_name'                   => ['sometimes', 'nullable', 'string', 'max:255'],
            'parent_birth_date' => ['required', 'date'],
            // 'parent_passport_series' => ['required', 'string', 'size:4'],
            'parent_passport_series' => ['required', 'string'],
            // 'parent_passport_number' => ['required', 'string', 'size:6'],
            'parent_passport_number' => ['required', 'string'],
            'parent_passport_issued_at' => ['required', 'date'],
            'parent_passport_issued_by' => ['required', 'string', 'max:255'],
            // 'parent_passport_department_code' => ['regex:/^\d{3}-\d{3}$/'],
            'parent_passport_department_code' => ['nullable', 'regex:/^\d{3}-\d{3}$/'],
            'parent_address' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            // Данные ребёнка
            'child_id.required' => 'ID ребёнка обязателен.',
            'child_id.integer' => 'ID ребёнка должен быть числом.',
            'child_id.exists' => 'Такой ребёнок не найден в базе.',

            'child_birth_date.required' => 'Введите дату рождения ребёнка.',
            'child_birth_date.date_format' => 'Дата рождения ребёнка должна быть в формате ДД.ММ.ГГГГ.',

            'child_birth_cert_number.required' => 'Введите номер свидетельства о рождении.',
            'child_birth_cert_number.max' => 'Номер свидетельства не должен превышать 50 символов.',

            'child_birth_cert_issued_at.required' => 'Введите дату выдачи свидетельства.',
            'child_birth_cert_issued_at.date_format' => 'Дата выдачи свидетельства должна быть в формате ДД.ММ.ГГГГ.',

            'child_birth_cert_issued_by.required' => 'Укажите, кем выдано свидетельство.',
            'child_birth_cert_issued_by.max' => 'Поле "Кем выдано" не должно превышать 255 символов.',

            // Данные родителя
            'parent_birth_date.required' => 'Введите дату рождения родителя.',
            'parent_birth_date.date_format' => 'Дата рождения родителя должна быть в формате ДД.ММ.ГГГГ.',

            'parent_passport_series.required' => 'Введите серию паспорта.',
            'parent_passport_series.size' => 'Серия паспорта должна содержать 4 цифры.',

            'parent_passport_number.required' => 'Введите номер паспорта.',
            'parent_passport_number.size' => 'Номер паспорта должен содержать 6 цифр.',

            'parent_passport_issued_at.required' => 'Введите дату выдачи паспорта.',
            'parent_passport_issued_at.date_format' => 'Дата выдачи паспорта должна быть в формате ДД.ММ.ГГГГ.',

            'parent_passport_issued_by.required' => 'Укажите, кем выдан паспорт.',
            'parent_passport_issued_by.max' => 'Поле "Кем выдан" не должно превышать 255 символов.',

            'parent_passport_department_code.required' => 'Введите код подразделения.',
            'parent_passport_department_code.regex' => 'Код подразделения должен быть в формате 000-000.',

            'parent_address.required' => 'Введите адрес проживания.',
            'parent_address.max' => 'Адрес не должен превышать 255 символов.',

            'parent_name.string' => 'ФИО родителя должно быть строкой.',
            'parent_name.max'    => 'ФИО родителя не должно превышать 255 символов.',
        ];
    }
}
