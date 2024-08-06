<?php

namespace App\Livewire\Forms;

use App\Models\Role;
use Livewire\Attributes\Rule;
use Livewire\Form;

class RoleForm extends Form
{
    #[Rule('required', message: "Nom role obligation", onUpdate: false)]
    public $name = '';
    #[Rule('required', message: "Selection type obligatoire", onUpdate: false)]
    public $is_for_school = false;

    public function create(array $input)
    {
        Role::create($input);
    }

    public function update(Role $role, array $input)
    {
        $role->update($input);
    }
}
