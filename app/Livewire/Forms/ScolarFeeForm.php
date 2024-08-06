<?php

namespace App\Livewire\Forms;

use App\Models\SchoolYear;
use App\Models\ScolarFee;
use Livewire\Attributes\Rule;
use Livewire\Form;

class ScolarFeeForm extends Form
{
    #[Rule('required', message: "Nom frais obligation", onUpdate: false)]
    public $name = '';
    #[Rule('required', message: "Montant obligatoire", onUpdate: false)]
    #[Rule('numeric', message: "Format numerique invalide", onUpdate: false)]
    public $amount = '';
    #[Rule('required', message: "Classe obligatoire", onUpdate: false)]
    public $class_room_id = '';

    #[Rule('required', message: "Categorie obligatoire", onUpdate: false)]
    public $category_fee_id
    = '';

    #[Rule('required', message: "Dévise obligatoire obligation", onUpdate: false)]
    public $currency = '';

    public function create(array $input)
    {
        ScolarFee::create($input);
    }

    public function update(ScolarFee $scolarFee, array $input)
    {
        $scolarFee->update($input);
    }
}
