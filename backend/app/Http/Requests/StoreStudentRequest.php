<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'student_code' => ['required', 'string', 'max:30', 'regex:/^[A-Za-z0-9_-]+$/', 'unique:students,student_code'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
