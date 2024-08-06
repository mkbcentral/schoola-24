<?php

namespace App\Livewire\Forms;

use App\Domain\Features\Payment\PaymentFeature;
use App\Models\Payment;
use Livewire\Attributes\Rule;
use Livewire\Form;

class PaymentForm extends Form
{
    #[Rule('required', message: "Mois obligation", onUpdate: false)]
    public $month = '';
    #[Rule('required', message: "TYpe frais obligatoire", onUpdate: false)]
    public $scolar_fee_id = '';
    #[Rule('required', message: "Categorie obligatoire", onUpdate: false)]
    public $category_fee_id = '';

    public function create(int  $registrationId): Payment|null
    {
        $input = [
            'month' => $this->month,
            'scolar_fee_id' => $this->scolar_fee_id,
            'registration_id' => $registrationId
        ];
        return PaymentFeature::create($input);
    }
    public function update(?Payment $payment, array $input): false
    {
        return $payment->update($input);
    }
}
