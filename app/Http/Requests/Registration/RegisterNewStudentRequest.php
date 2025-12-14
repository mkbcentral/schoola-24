<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class RegisterNewStudentRequest extends FormRequest
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
            // Student data
            'student.name' => ['required', 'string', 'max:255'],
            'student.gender' => ['required', 'string', 'in:M,F'],
            'student.place_of_birth' => ['required', 'string', 'max:255'],
            'student.date_of_birth' => ['required', 'date', 'before:today'],
            'student.responsible_student_id' => ['nullable', 'integer', 'exists:responsible_students,id'],

            // Registration data
            'registration.class_room_id' => ['required', 'integer', 'exists:class_rooms,id'],
            'registration.registration_fee_id' => ['nullable', 'integer', 'exists:registration_fees,id'],
            'registration.school_year_id' => ['nullable', 'integer', 'exists:school_years,id'],
            'registration.rate_id' => ['nullable', 'integer', 'exists:rates,id'],
            'registration.code' => ['nullable', 'string', 'max:50'],
            'registration.registration_number' => ['nullable', 'string', 'max:50'],
            'registration.is_registered' => ['boolean'],
            'registration.is_fee_exempted' => ['boolean'],
            'registration.is_under_derogation' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'student.name.required' => 'Le nom de l\'élève est requis.',
            'student.gender.required' => 'Le genre est requis.',
            'student.gender.in' => 'Le genre doit être M (Masculin) ou F (Féminin).',
            'student.place_of_birth.required' => 'Le lieu de naissance est requis.',
            'student.date_of_birth.required' => 'La date de naissance est requise.',
            'student.date_of_birth.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'student.responsible_student_id.exists' => 'Ce responsable n\'existe pas.',

            'registration.class_room_id.required' => 'La classe est requise.',
            'registration.class_room_id.exists' => 'Cette classe n\'existe pas.',
            'registration.registration_fee_id.exists' => 'Ces frais d\'inscription n\'existent pas.',
            'registration.school_year_id.exists' => 'Cette année scolaire n\'existe pas.',
        ];
    }
}
