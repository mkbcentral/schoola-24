<?php

namespace App\DTOs\User;

class RoleDTO
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?bool $is_for_school = true,
    ) {}

    /**
     * Créer un DTO depuis un tableau
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            is_for_school: $data['is_for_school'] ?? true,
        );
    }

    /**
     * Convertir en tableau pour la création
     */
    public function toCreateArray(): array
    {
        return [
            'name' => $this->name,
            'is_for_school' => $this->is_for_school,
        ];
    }

    /**
     * Convertir en tableau pour la mise à jour
     */
    public function toUpdateArray(): array
    {
        $data = [];

        if ($this->name !== null) {
            $data['name'] = $this->name;
        }

        if ($this->is_for_school !== null) {
            $data['is_for_school'] = $this->is_for_school;
        }

        return $data;
    }
}
