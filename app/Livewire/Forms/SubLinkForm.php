<?php

namespace App\Livewire\Forms;

use App\Models\SubLink;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SubLinkForm extends Form
{
    #[Validate('required', message: 'Nom menu obligatoire')]
    public  $name = '';
    #[Validate('required', message: 'Icon menu obligatoire')]
    public  $icon = '';
    #[Validate('required', message: 'Route menu obligatoire')]
    public  $route = '';
    #[Validate('required', message: 'Menu parent obligatoire')]
    #[Validate('numeric', message: 'Format numérique invalide')]
    public  $multi_app_link_id = '';
    public function create(): SubLink
    {
        return SubLink::create($this->all());
    }
    public function update(SubLink $subLink): bool
    {
        return $subLink->update($this->all());
    }
}
