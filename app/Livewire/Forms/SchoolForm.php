<?php

namespace App\Livewire\Forms;

use App\Models\School;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SchoolForm extends Form
{

    #[Validate('required', message: 'Nom utilisateur obligatoire', onUpdate: false)]
    public $name = '';

    #[Validate('nullable',  onUpdate: false)]
    #[Validate('image', message: 'Le logo doit être une image', onUpdate: false)]
    #[Validate('max:1024', message: 'La taille maximale est 1Mb', onUpdate: false)]
    public $logo;

    #[Validate('required', message: 'N° Tél obligation', onUpdate: false)]
    #[Validate('min:9', message: 'Minimum 9 caractères', onUpdate: false)]
    public $phone = '';

    #[Validate('required', message: 'Adresse email abligatoire', onUpdate: false)]
    #[Validate('min:6', message: 'Minimum 6 caractères', onUpdate: false)]
    #[Validate('email', message: 'Format email invalide', onUpdate: false)]
    public $email = '';

    #[Validate('required', message: 'Nom mode obligatoire', onUpdate: false)]
    public $app_status = '';
    #[Validate('required', message: 'Nom status obligatoire', onUpdate: false)]
    public $school_status = '';

    public function create(): School
    {
        $inputs = $this->all();
        $inputs['logo'] = $this->getLohoPath();
        return School::create($inputs);
    }

    public function update(School $school): bool
    {
        $inputs = $this->all();
        $inputs['logo'] = $this->getLohoPath();
        return $school->update($inputs);
    }

    public function getLohoPath(): string
    {
        if ($this->logo != null) {
            $logoPath =  $this->logo->store('school/logo', 'public');
            return $logoPath;
        } else {
            return '';
        }
    }
}
