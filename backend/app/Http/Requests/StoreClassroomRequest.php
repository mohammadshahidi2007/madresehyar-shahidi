<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Deny by default until school roles and policies are wired.
        return false;
    }

    public function rules(): array
    {
        return [
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'grade' => ['required', 'integer', Rule::in([7, 8, 9])],
            'section' => [
                'required',
                'string',
                'max:20',
                'regex:/^[\p{L}\p{N} _-]+$/u',
                Rule::unique('classrooms', 'section')->where(
                    fn ($query) => $query
                        ->where('academic_year_id', $this->input('academic_year_id'))
                        ->where('grade', $this->input('grade'))
                ),
            ],
        ];
    }
}
