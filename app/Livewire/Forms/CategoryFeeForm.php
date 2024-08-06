<?php

namespace App\Livewire\Forms;

use App\Models\CategoryFee;
use App\Models\School;
use App\Models\SchoolYear;
use Livewire\Attributes\Rule;
use Livewire\Form;

class CategoryFeeForm extends Form
{
    #[Rule('required', message: "Nom categrie obligation", onUpdate: false)]
    public $name = '';
    #[Rule('required', message: "Selection type obligatoire", onUpdate: false)]
    public $is_paid_in_installment = false;
    #[Rule('required', message: "Selection type obligatoire", onUpdate: false)]
    public $is_paid_for_registration = false;
    #[Rule('required', message: "Selection type obligatoire", onUpdate: false)]
    public $is_state_fee = false;

    public function create(array $input)
    {
        $input['school_id'] = School::DEFAULT_SCHOOL_ID();
        $input['school_year_id'] = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        CategoryFee::create($input);
    }
    public function update(CategoryFee $categoryFee, array $input)
    {
        $categoryFee->update($input);
    }
}