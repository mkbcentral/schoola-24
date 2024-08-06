<?php

namespace App\Livewire\Forms;

use App\Models\School;
use App\Models\Section;
use Livewire\Attributes\Rule;
use Livewire\Form;

class SectionForm extends Form
{
    #[Rule('required', message: "Nom section obligation", onUpdate: false)]
    public $name = '';

    public function create(array $input)
    {
        $input['school_id'] = School::DEFAULT_SCHOOL_ID();
        Section::create($input);
    }

    public function update(Section $section, array $input)
    {
        $section->update($input);
    }
}
