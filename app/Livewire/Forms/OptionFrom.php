<?php

namespace App\Livewire\Forms;

use App\Models\Option;
use Livewire\Attributes\Rule;
use Livewire\Form;

class OptionFrom extends Form
{

    #[Rule('required', message: "Nom option obligation", onUpdate: false)]
    public $name = '';
    #[Rule('required', message: "Type section obligation", onUpdate: false)]
    public $section_id = '';

    public function create(array $input): void
    {
        Option::create($input);
    }
    public function update(Option $option, array $input): void
    {
        $option->update($input);
    }
}
