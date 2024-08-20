<?php

namespace App\Livewire\Forms;

use App\Models\OtherExpense;
use App\Models\SchoolYear;
use Livewire\Attributes\Validate;
use Livewire\Form;

class OtherExpenseForm extends Form
{
    #[Validate('required', message: 'Déscription obligatoire')]
    public  $description = '';
    #[Validate('required', message: 'Dévise obligatoire')]
    public  $currency = '';
    #[Validate('required', message: "Montant obligatoire")]
    #[Validate('numeric', message: "Format numérique invalide")]
    public  $amount;
    #[Validate('required', message: "Type frais obligatoire")]
    #[Validate('numeric', message: "Format numérique invalide")]
    public $category_expense_id;
    #[Validate('required', message: "Source dépense obligatoire")]
    #[Validate('numeric', message: "Format numérique invalide")]
    public $other_source_expense_id;
    #[Validate('required', message: "Mois obligatoire")]
    public  $month;
    #[Validate('required', message: "Date de création obligatoire")]
    #[Validate('date', message: "Format date invalide")]
    public  $created_at;


    public function create(): OtherExpense
    {
        $inputs = $this->all();
        $inputs['school_year_id'] = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        return OtherExpense::create($inputs);
    }

    public function update(OtherExpense $otherExpense): bool
    {
        return $otherExpense->update($this->all());
    }
}
