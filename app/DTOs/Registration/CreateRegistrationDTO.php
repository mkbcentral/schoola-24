<?php

namespace App\DTOs\Registration;

class CreateRegistrationDTO
{
    public function __construct(
        public readonly ?int $student_id,
        public readonly int $class_room_id,
        public readonly ?int $registration_fee_id = null,
        public readonly ?int $school_year_id = null,
        public readonly ?int $rate_id = null,
        public readonly ?string $code = null,
        public readonly ?string $registration_number = null,
        public readonly bool $is_old = false,
        public readonly bool $is_registered = true,
        public readonly bool $is_fee_exempted = false,
        public readonly bool $is_under_derogation = false,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            student_id: $data['student_id'] ?? null,
            class_room_id: $data['class_room_id'],
            registration_fee_id: $data['registration_fee_id'] ?? null,
            school_year_id: $data['school_year_id'] ?? null,
            rate_id: $data['rate_id'] ?? null,
            code: $data['code'] ?? null,
            registration_number: $data['registration_number'] ?? null,
            is_old: $data['is_old'] ?? false,
            is_registered: $data['is_registered'] ?? true,
            is_fee_exempted: $data['is_fee_exempted'] ?? false,
            is_under_derogation: $data['is_under_derogation'] ?? false,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'student_id' => $this->student_id,
            'class_room_id' => $this->class_room_id,
            'registration_fee_id' => $this->registration_fee_id,
            'school_year_id' => $this->school_year_id,
            'rate_id' => $this->rate_id,
            'code' => $this->code,
            'registration_number' => $this->registration_number,
            'is_old' => $this->is_old,
            'is_registered' => $this->is_registered,
            'is_fee_exempted' => $this->is_fee_exempted,
            'is_under_derogation' => $this->is_under_derogation,
        ], fn($value) => $value !== null);
    }
}
