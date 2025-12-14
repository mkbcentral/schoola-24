<?php

namespace App\Livewire\Forms;

use App\Models\Role;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RoleForm extends Form
{
    public ?Role $role = null;

    #[Validate]
    public $name = '';

    #[Validate]
    public $is_for_school = 1;

    /**
     * Règles de validation dynamiques
     */
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                ValidationRule::unique('roles', 'name')->ignore($this->role),
            ],
            'is_for_school' => [
                'required',
                'boolean',
            ],
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    protected function messages()
    {
        return [
            'name.required' => 'Le nom du rôle est obligatoire.',
            'name.min' => 'Le nom doit contenir au moins 3 caractères.',
            'name.max' => 'Le nom ne peut pas dépasser 100 caractères.',
            'name.unique' => 'Ce nom de rôle existe déjà.',
            
            'is_for_school.required' => 'Le type de rôle est obligatoire.',
            'is_for_school.boolean' => 'Le type de rôle doit être valide.',
        ];
    }

    /**
     * Initialiser le formulaire avec un rôle existant
     */
    public function setRole(Role $role)
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->is_for_school = $role->is_for_school;
    }

    /**
     * Créer un nouveau rôle
     */
    public function store()
    {
        $this->validate();

        Role::create([
            'name' => $this->name,
            'is_for_school' => $this->is_for_school,
        ]);

        $this->reset();
    }

    /**
     * Mettre à jour un rôle existant
     */
    public function update()
    {
        $this->validate();

        $this->role->update([
            'name' => $this->name,
            'is_for_school' => $this->is_for_school,
        ]);
    }
}
