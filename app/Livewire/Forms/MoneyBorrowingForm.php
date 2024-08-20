<?php

namespace App\Livewire\Forms;

use App\Models\MoneyBorrowing;
use App\Models\School;
use App\Models\SchoolYear;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MoneyBorrowingForm extends Form
{
    #[Validate('required', message: 'Déscription obligatoire')]
    public  $description = '';
    #[Validate('required', message: 'Dévise obligatoire')]
    public  $currency = '';
    #[Validate('required', message: "Montant obligatoire")]
    #[Validate('numeric', message: "Format numérique invalide")]
    public  $amount;
    #[Validate('required', message: "Mois obligatoire")]
    public  $month;
    #[Validate('required', message: "Date de création obligatoire")]
    #[Validate('date', message: "Format date invalide")]
    public  $created_at;


    public function create(): MoneyBorrowing
    {
        $inputs = $this->all();
        $inputs['school_id'] = School::DEFAULT_SCHOOL_ID();
        $inputs['school_year_id'] = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        return MoneyBorrowing::create($inputs);
    }

    public function update(MoneyBorrowing $moneyBorrowing): bool
    {
        return $moneyBorrowing->update($this->all());
    }
}
