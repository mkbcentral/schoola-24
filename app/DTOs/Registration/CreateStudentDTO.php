<?php

namespace App\DTOs\Registration;

class CreateStudentDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $gender,
        public readonly string $place_of_birth,
        public readonly string $date_of_birth,
        public readonly ?int $responsible_student_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            gender: $data['gender'],
            place_of_birth: $data['place_of_birth'],
            date_of_birth: $data['date_of_birth'],
            responsible_student_id: $data['responsible_student_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'gender' => $this->gender,
            'place_of_birth' => $this->place_of_birth,
            'date_of_birth' => $this->date_of_birth,
            'responsible_student_id' => $this->responsible_student_id,
        ];
    }
}
