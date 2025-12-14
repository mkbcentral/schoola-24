<?php

namespace App\DTOs\Registration;

class RegistrationStatsDTO
{
    public function __construct(
        public readonly int $total,
        public readonly int $total_male,
        public readonly int $total_female,
        public readonly array $by_section,
        public readonly array $by_option,
        public readonly array $by_class,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            total: $data['total'],
            total_male: $data['total_male'],
            total_female: $data['total_female'],
            by_section: $data['by_section'],
            by_option: $data['by_option'],
            by_class: $data['by_class'],
        );
    }

    public function toArray(): array
    {
        return [
            'total' => $this->total,
            'total_male' => $this->total_male,
            'total_female' => $this->total_female,
            'by_section' => $this->by_section,
            'by_option' => $this->by_option,
            'by_class' => $this->by_class,
        ];
    }
}
