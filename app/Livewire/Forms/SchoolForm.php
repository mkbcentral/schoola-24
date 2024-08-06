<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Form;

class SchoolForm extends Form
{
    #[Rule('required', message: 'Nom utilisateur obligatoire', onUpdate: false)]
    public $name = '';

    #[Rule('required', message: 'N° Tél obligation', onUpdate: false)]
    #[Rule('min:9', message: 'Minimum 9 caractères', onUpdate: false)]
    public $phone = '';

    #[Rule('nullable', onUpdate: false)]
    #[Rule('min:6', message: 'Minimum 6 caractères', onUpdate: false)]
    #[Rule('email', message: 'Format email invalide', onUpdate: false)]
    public $email = '';

    #[Rule('required', message: 'Nom mode obligatoire', onUpdate: false)]
    public $app_status = '';
    #[Rule('required', message: 'Nom status obligatoire', onUpdate: false)]
    public $school_status = '';
}
