<?php

namespace App\Livewire\Forms;

use App\Models\MultiAppLink;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MultiLinkForm extends Form
{
    #[Validate('required', message: 'Nom menu obligatoire')]
    public $name = '';

    #[Validate('required', message: 'Icon menu obligatoire')]
    public $icon = '';

    public function create(): MultiAppLink
    {
        return MultiAppLink::create($this->all());
    }

    public function update(MultiAppLink $multiAppLink): bool
    {
        return $multiAppLink->update($this->all());
    }
}
