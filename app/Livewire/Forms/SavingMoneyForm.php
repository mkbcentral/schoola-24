<?php

namespace App\Livewire\Forms;

use App\Models\SavingMoney;
use App\Models\School;
use App\Models\SchoolYear;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SavingMoneyForm extends Form
{
    #[Validate('required', message: 'Dévise obligatoire')]
    public $currency = '';

    #[Validate('required', message: 'Montant obligatoire')]
    #[Validate('numeric', message: 'Format numérique invalide')]
    public $amount;

    #[Validate('required', message: 'Mois obligatoire')]
    public $month;

    #[Validate('required', message: 'Date de création obligatoire')]
    #[Validate('date', message: 'Format date invalide')]
    public $created_at;

    public function create(): SavingMoney
    {
        $inputs = $this->all();
        $inputs['school_id'] = School::DEFAULT_SCHOOL_ID();
        $inputs['school_year_id'] = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();

        return SavingMoney::create($inputs);
    }

    public function update(SavingMoney $savingMoney): bool
    {
        return $savingMoney->update($this->all());
    }
}
