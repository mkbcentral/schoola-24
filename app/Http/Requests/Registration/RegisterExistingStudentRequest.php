<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class RegisterExistingStudentRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'class_room_id' => ['required', 'integer', 'exists:class_rooms,id'],
            'registration_fee_id' => ['nullable', 'integer', 'exists:registration_fees,id'],
            'school_year_id' => ['nullable', 'integer', 'exists:school_years,id'],
            'rate_id' => ['nullable', 'integer', 'exists:rates,id'],
            'code' => ['nullable', 'string', 'max:50'],
            'registration_number' => ['nullable', 'string', 'max:50'],
            'is_registered' => ['boolean'],
            'is_fee_exempted' => ['boolean'],
            'is_under_derogation' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'L\'identifiant de l\'élève est requis.',
            'student_id.exists' => 'Cet élève n\'existe pas.',
            'class_room_id.required' => 'La classe est requise.',
            'class_room_id.exists' => 'Cette classe n\'existe pas.',
            'registration_fee_id.exists' => 'Ces frais d\'inscription n\'existent pas.',
            'school_year_id.exists' => 'Cette année scolaire n\'existe pas.',
        ];
    }
}
