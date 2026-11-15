<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Deny by default until school roles and policies are wired.
        return false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:20', 'unique:academic_years,name'],
            'starts_on' => ['required', 'date'],
            'ends_on' => ['required', 'date', 'after:starts_on'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
