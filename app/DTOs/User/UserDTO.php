<?php

namespace App\DTOs\User;

class UserDTO
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $username = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $avatar = null,
        public ?int $role_id = null,
        public ?string $password = null,
        public ?bool $is_active = true,
        public ?bool $is_on_line = false,
        public ?int $school_id = null,
        public ?int $work_on_year = null,
    ) {}

    /**
     * Créer un DTO depuis un tableau
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            username: $data['username'] ?? null,
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            avatar: $data['avatar'] ?? null,
            role_id: $data['role_id'] ?? null,
            password: $data['password'] ?? null,
            is_active: $data['is_active'] ?? true,
            is_on_line: $data['is_on_line'] ?? false,
            school_id: $data['school_id'] ?? null,
            work_on_year: $data['work_on_year'] ?? null,
        );
    }

    /**
     * Convertir en tableau pour la création
     */
    public function toCreateArray(): array
    {
        $data = [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'role_id' => $this->role_id,
            'is_active' => $this->is_active,
            'is_on_line' => $this->is_on_line,
            'school_id' => $this->school_id,
            'work_on_year' => $this->work_on_year,
        ];

        if ($this->avatar) {
            $data['avatar'] = $this->avatar;
        }

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        return array_filter($data, fn($value) => $value !== null);
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

        if ($this->username !== null) {
            $data['username'] = $this->username;
        }

        if ($this->email !== null) {
            $data['email'] = $this->email;
        }

        if ($this->phone !== null) {
            $data['phone'] = $this->phone;
        }

        if ($this->avatar !== null) {
            $data['avatar'] = $this->avatar;
        }

        if ($this->role_id !== null) {
            $data['role_id'] = $this->role_id;
        }

        if ($this->is_active !== null) {
            $data['is_active'] = $this->is_active;
        }

        if ($this->is_on_line !== null) {
            $data['is_on_line'] = $this->is_on_line;
        }

        if ($this->work_on_year !== null) {
            $data['work_on_year'] = $this->work_on_year;
        }

        // Ne pas inclure le mot de passe dans les mises à jour standards
        // Utiliser ResetPasswordAction pour cela

        return $data;
    }
}
