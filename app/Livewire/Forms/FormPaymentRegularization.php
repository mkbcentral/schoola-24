<?php

namespace App\Livewire\Forms;

use App\Models\PaymentRegularization;
use App\Models\School;
use App\Models\SchoolYear;
use Livewire\Attributes\Rule;
use Livewire\Form;

class FormPaymentRegularization extends Form
{
    #[Rule('required', message: 'Nom élève obligation', onUpdate: false)]
    public $name = '';

    #[Rule('required', message: 'Mois obligation', onUpdate: false)]
    public $month = '';

    #[Rule('required', message: 'Montant obligation', onUpdate: false)]
    #[Rule('numeric', message: 'Format numérique invalide', onUpdate: false)]
    public $amount = '';

    #[Rule('required', message: 'Categorie frais obligatoire', onUpdate: false)]
    public $category_fee_id = '';

    #[Rule('required', message: 'Classe obligatoire', onUpdate: false)]
    public $class_room_id = '';

    #[Rule('required', message: 'Date création obligatoire', onUpdate: false)]
    #[Rule('date', message: 'Format date invalide', onUpdate: false)]
    public $created_at = '';

    public function create(): ?PaymentRegularization
    {
        return PaymentRegularization::create([
            'name' => $this->name,
            'month' => $this->month,
            'amount' => $this->amount,
            'category_fee_id' => $this->category_fee_id,
            'class_room_id' => $this->class_room_id,
            'created_at' => $this->created_at,
            'school_id' => School::DEFAULT_SCHOOL_ID(),
            'school_year_id' => SchoolYear::DEFAULT_SCHOOL_YEAR_ID(),
        ]);
    }

    public function update(PaymentRegularization $paymentRegularization)
    {
        $paymentRegularization->update([
            'name' => $this->name,
            'month' => $this->month,
            'amount' => $this->amount,
            'category_fee_id' => $this->category_fee_id,
            'class_room_id' => $this->class_room_id,
            'created_at' => $this->created_at,
        ]);
    }
}
