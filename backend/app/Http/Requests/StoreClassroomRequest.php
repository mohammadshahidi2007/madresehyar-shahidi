<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'grade' => ['required', 'integer', Rule::in([7, 8, 9])],
            'section' => ['required', 'string', 'max:20', 'regex:/^[\p{L}\p{N} _-]+$/u'],
        ];
    }
}
