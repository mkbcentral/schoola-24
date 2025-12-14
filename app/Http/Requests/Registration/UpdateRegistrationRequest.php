<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRegistrationRequest extends FormRequest
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
            'class_room_id' => ['nullable', 'integer', 'exists:class_rooms,id'],
            'registration_fee_id' => ['nullable', 'integer', 'exists:registration_fees,id'],
            'rate_id' => ['nullable', 'integer', 'exists:rates,id'],
            'code' => ['nullable', 'string', 'max:50'],
            'registration_number' => ['nullable', 'string', 'max:50'],
            'is_registered' => ['nullable', 'boolean'],
            'is_fee_exempted' => ['nullable', 'boolean'],
            'is_under_derogation' => ['nullable', 'boolean'],
            'abandoned' => ['nullable', 'boolean'],
            'class_changed' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'class_room_id.exists' => 'Cette classe n\'existe pas.',
            'registration_fee_id.exists' => 'Ces frais d\'inscription n\'existent pas.',
            'rate_id.exists' => 'Ce tarif n\'existe pas.',
        ];
    }
}
