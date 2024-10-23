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
    #[Rule('required', message: "Devise obligatoire obligation", onUpdate: false)]
    public $currency = '';
    #[Rule('required', message: "Selection type obligatoire", onUpdate: false)]
    public $is_paid_in_installment = false;
    #[Rule('required', message: "Selection type obligatoire", onUpdate: false)]
    public $is_paid_for_registration = false;
    #[Rule('required', message: "Selection type obligatoire", onUpdate: false)]
    public $is_state_fee = false;
    #[Rule('required', message: "Selection type obligatoire", onUpdate: false)]
    public $is_for_dash = false;
    #[Rule('required', message: "Selection type obligatoire", onUpdate: false)]
    public $is_accessory = false;

    public function create(array $input)
    {
        $input['school_id'] = School::DEFAULT_SCHOOL_ID();
        $input['school_year_id'] = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        CategoryFee::create($input);
    }
    public function update(CategoryFee $categoryFee, array $input)
    {
        $input['school_year_id'] = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        $categoryFee->update($input);
    }
}
