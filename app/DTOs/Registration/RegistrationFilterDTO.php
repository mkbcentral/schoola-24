<?php

namespace App\DTOs\Registration;

class RegistrationFilterDTO
{
    public function __construct(
        public readonly ?int $school_year_id = null,
        public readonly ?int $section_id = null,
        public readonly ?int $option_id = null,
        public readonly ?int $class_room_id = null,
        public readonly ?string $gender = null,
        public readonly ?string $date_from = null,
        public readonly ?string $date_to = null,
        public readonly ?bool $is_old = null,
        public readonly ?bool $abandoned = null,
        public readonly ?bool $is_registered = null,
    ) {}

    public static function fromArray(array $data): self
    {
        // Helper pour convertir les valeurs vides en null et caster correctement
        $toInt = fn($value) => $value !== null && $value !== '' ? (int) $value : null;
        $toString = fn($value) => $value !== null && $value !== '' ? (string) $value : null;
        $toBool = fn($value) => $value !== null && $value !== '' ? (bool) $value : null;

        return new self(
            school_year_id: $toInt($data['school_year_id'] ?? null),
            section_id: $toInt($data['section_id'] ?? null),
            option_id: $toInt($data['option_id'] ?? null),
            class_room_id: $toInt($data['class_room_id'] ?? null),
            gender: $toString($data['gender'] ?? null),
            date_from: $toString($data['date_from'] ?? null),
            date_to: $toString($data['date_to'] ?? null),
            is_old: $toBool($data['is_old'] ?? null),
            abandoned: $toBool($data['abandoned'] ?? null),
            is_registered: $toBool($data['is_registered'] ?? null),
        );
    }
}
