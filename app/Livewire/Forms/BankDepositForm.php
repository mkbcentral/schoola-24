<?php

namespace App\Livewire\Forms;

use App\Models\BankDeposit;
use App\Models\School;
use App\Models\SchoolYear;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BankDepositForm extends Form
{
    #[Validate('required', message: 'Dévise obligatoire')]
    public $currency = '';

    #[Validate('required', message: 'Description obligatoire')]
    public $description = '';

    #[Validate('required', message: 'Montant obligatoire')]
    #[Validate('numeric', message: 'Format numérique invalide')]
    public $amount;

    #[Validate('required', message: 'Mois obligatoire')]
    public $month;

    #[Validate('required', message: 'Date de création obligatoire')]
    #[Validate('date', message: 'Format date invalide')]
    public $created_at;

    #[Validate('required', message: 'Source opération obligatoire')]
    #[Validate('numeric', message: 'Format numérique invalide')]
    public $category_fee_id;

    public function create(): BankDeposit
    {
        $inputs = $this->all();
        $inputs['school_id'] = School::DEFAULT_SCHOOL_ID();
        $inputs['school_year_id'] = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();

        return BankDeposit::create($inputs);
    }

    public function update(BankDeposit $bankDeposit): bool
    {
        return $bankDeposit->update($this->all());
    }
}
