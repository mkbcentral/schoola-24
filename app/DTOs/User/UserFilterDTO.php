<?php

namespace App\DTOs\User;

class UserFilterDTO
{
    public function __construct(
        public ?string $search = null,
        public ?int $role_id = null,
        public ?bool $is_active = null,
        public ?bool $is_on_line = null,
        public ?int $school_id = null,
        public ?int $work_on_year = null,
    ) {}

    /**
     * Créer un DTO depuis un tableau
     */
    public static function fromArray(array $data): self
    {
        return new self(
            search: $data['search'] ?? null,
            role_id: $data['role_id'] ?? null,
            is_active: isset($data['is_active']) ? filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN) : null,
            is_on_line: isset($data['is_on_line']) ? filter_var($data['is_on_line'], FILTER_VALIDATE_BOOLEAN) : null,
            school_id: $data['school_id'] ?? null,
            work_on_year: $data['work_on_year'] ?? null,
        );
    }

    /**
     * Convertir en tableau
     */
    public function toArray(): array
    {
        return array_filter([
            'search' => $this->search,
            'role_id' => $this->role_id,
            'is_active' => $this->is_active,
            'is_on_line' => $this->is_on_line,
            'school_id' => $this->school_id,
            'work_on_year' => $this->work_on_year,
        ], fn($value) => $value !== null);
    }
}
