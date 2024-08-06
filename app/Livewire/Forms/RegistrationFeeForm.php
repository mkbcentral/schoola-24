<?php

namespace App\Livewire\Forms;

use App\Models\RegistrationFee;
use App\Models\SchoolYear;
use Livewire\Attributes\Rule;
use Livewire\Form;

class RegistrationFeeForm extends Form
{
    #[Rule('required', message: "Nom frais obligation", onUpdate: false)]
    public $name = '';
    #[Rule('required', message: "Montant obligatoire", onUpdate: false)]
    #[Rule('numeric', message: "Format numerique invalide", onUpdate: false)]
    public $amount = '';
    #[Rule('required', message: "Section obligatoire", onUpdate: false)]
    public $option_id = '';

    #[Rule('required', message: "Categorie obligatoire", onUpdate: false)]
    public $category_registration_fee_id   = '';

    #[Rule('required', message: "Dévise obligatoire obligation", onUpdate: false)]
    public $currency = '';

    public function create(array $input)
    {
        $input['school_year_id'] = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        RegistrationFee::create($input);
    }

    public function update(RegistrationFee $registrationFee, array $input)
    {
        $registrationFee->update($input);
    }
}
