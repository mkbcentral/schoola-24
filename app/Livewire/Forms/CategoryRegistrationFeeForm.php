<?php

namespace App\Livewire\Forms;

use App\Models\CategoryRegistrationFee;
use App\Models\School;
use Livewire\Attributes\Rule;
use Livewire\Form;

class CategoryRegistrationFeeForm extends Form
{
    #[Rule('required', message: "Nom categrie obligation", onUpdate: false)]
    public $name = '';
    #[Rule('required', message: "Selection type obligatoire", onUpdate: false)]
    public $is_old = false;

    public function create(array $input)
    {
        $input['school_id'] = School::DEFAULT_SCHOOL_ID();
        CategoryRegistrationFee::create($input);
    }
    public function update(CategoryRegistrationFee $categoryRegistrationFee, array $input)
    {
        $categoryRegistrationFee->update($input);
    }
}
