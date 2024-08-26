<?php

namespace App\Livewire\Forms;

use App\Models\Role;
use App\Models\User;
use Livewire\Attributes\Rule;
use Livewire\Form;

class UserForm extends Form
{
    #[Rule('required', message: 'Nom utilisateur obligatoire', onUpdate: false)]
    public $name = '';

    #[Rule('required', message: 'N° Tél obligation', onUpdate: false)]
    #[Rule('min:9', message: 'Minimum 9 caractères', onUpdate: false)]
    public $phone = '';

    #[Rule('required', message: 'Role utilisateur obligatoire',  onUpdate: false)]
    public $role_id = '';

    #[Rule('nullable', onUpdate: false)]
    #[Rule('min:6', message: 'Minimum 6 caractères', onUpdate: false)]
    #[Rule('email', message: 'Format email invalide', onUpdate: false)]
    public $email = '';

    #[Rule('required', message: 'Role utilisateur obligatoire',  onUpdate: false)]
    public $is_active = false;

    public function create(array $input): void
    {
        User::create($input);
    }
    public function update(User $user, array $input): void
    {
        $user->update($input);
    }
}
