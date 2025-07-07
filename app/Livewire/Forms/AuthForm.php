<?php

namespace App\Livewire\Forms;

use App\Domain\Features\AuthFeatue\AuthFeature;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AuthForm extends Form
{
    #[Validate('required', message: 'Adresse mail obligatoire', onUpdate: false)]
    //#[Validate('email', message: 'Adresse mail invalide', onUpdate: false)]
    #[Validate('min:4', message: 'Taille username faible', onUpdate: false)]
    public string $login = '';
    #[Validate('required', message: 'Mot de passe obligatoire', onUpdate: false)]
    #[Validate('min:4', message: 'Mot de passe faible', onUpdate: false)]
    public string $password = '';

    public  function login(): bool
    {
        $inputs = [
            'login' => $this->login,
            'password' => $this->password,
        ];
        return AuthFeature::login($inputs);
    }
}
