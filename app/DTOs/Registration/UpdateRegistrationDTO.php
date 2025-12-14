<?php

namespace App\DTOs\Registration;

class UpdateRegistrationDTO
{
    public function __construct(
        public readonly ?int $class_room_id = null,
        public readonly ?int $registration_fee_id = null,
        public readonly ?int $rate_id = null,
        public readonly ?string $code = null,
        public readonly ?string $registration_number = null,
        public readonly ?bool $is_registered = null,
        public readonly ?bool $is_fee_exempted = null,
        public readonly ?bool $is_under_derogation = null,
        public readonly ?bool $abandoned = null,
        public readonly ?bool $class_changed = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            class_room_id: $data['class_room_id'] ?? null,
            registration_fee_id: $data['registration_fee_id'] ?? null,
            rate_id: $data['rate_id'] ?? null,
            code: $data['code'] ?? null,
            registration_number: $data['registration_number'] ?? null,
            is_registered: $data['is_registered'] ?? null,
            is_fee_exempted: $data['is_fee_exempted'] ?? null,
            is_under_derogation: $data['is_under_derogation'] ?? null,
            abandoned: $data['abandoned'] ?? null,
            class_changed: $data['class_changed'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'class_room_id' => $this->class_room_id,
            'registration_fee_id' => $this->registration_fee_id,
            'rate_id' => $this->rate_id,
            'code' => $this->code,
            'registration_number' => $this->registration_number,
            'is_registered' => $this->is_registered,
            'is_fee_exempted' => $this->is_fee_exempted,
            'is_under_derogation' => $this->is_under_derogation,
            'abandoned' => $this->abandoned,
            'class_changed' => $this->class_changed,
        ], fn($value) => $value !== null);
    }
}
