<?php

namespace App\Livewire\Forms;

use App\Domain\Features\Payment\PaymentFeature;
use App\Models\Payment;
use App\Models\ScolarFee;
use Livewire\Attributes\Rule;
use Livewire\Form;

class PaymentForm extends Form
{
    #[Rule('required', message: 'Mois obligation', onUpdate: false)]
    public $month = '';

    #[Rule('required', message: 'Categorie obligatoire', onUpdate: false)]
    public $category_fee_id = '';

    #[Rule('required', message: 'Date création obligatoire', onUpdate: false)]
    #[Rule('date', message: 'Format date invalide', onUpdate: false)]
    public $created_at = '';

    public function create(int $registrationId, ScolarFee $scolarFee): ?Payment
    {
        $input = [
            'month' => $this->month,
            'scolar_fee_id' => $scolarFee->id,
            'registration_id' => $registrationId,
            'created_at' => $this->created_at,
        ];

        return PaymentFeature::create($input);
    }

    public function update(?Payment $payment, $scolar_fee_id): void
    {
        $input = [
            'month' => $this->month,
            'scolar_fee_id' => $scolar_fee_id,
            'created_at' => $this->created_at,
        ];
        $payment->update($input);
    }
}
