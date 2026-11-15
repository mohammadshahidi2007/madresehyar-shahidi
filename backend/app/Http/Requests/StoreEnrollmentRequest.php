<?php

namespace App\\Http\\Requests;

use Illuminate\\Foundation\\Http\\FormRequest;
use Illuminate\\Validation\\Rule;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'classroom_id' => ['required', 'integer', 'exists:classrooms,id'],
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'enrolled_on' => ['required', 'date'],
            'left_on' => ['nullable', 'date', 'after_or_equal:enrolled_on'],
            'status' => ['required', Rule::in(['active', 'completed', 'transferred', 'withdrawn'])],
        ];
    }
}
