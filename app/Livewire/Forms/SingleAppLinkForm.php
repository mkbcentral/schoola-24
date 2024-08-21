<?php

namespace App\Livewire\Forms;

use App\Models\SingleAppLink;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SingleAppLinkForm extends Form
{
    #[Validate('required', message: 'Nom menu obligatoire')]
    public  $name = '';
    #[Validate('required', message: 'Icon menu obligatoire')]
    public  $icon = '';
    #[Validate('required', message: 'Route menu obligatoire')]
    public  $route = '';


    public function create(): SingleAppLink
    {
        return SingleAppLink::create($this->all());
    }

    public function update(SingleAppLink $singleAppLink): bool
    {
        return $singleAppLink->update($this->all());
    }
}
