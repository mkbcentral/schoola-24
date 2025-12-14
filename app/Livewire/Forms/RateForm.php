<?php

namespace App\Livewire\Forms;

use App\Models\Rate;
use App\Models\School;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RateForm extends Form
{
    public ?Rate $rate = null;

    #[Validate]
    public $amount = '';

    #[Validate]
    public $is_changed = false;

    /**
     * Règles de validation
     */
    protected function rules()
    {
        return [
            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],
            'is_changed' => [
                'boolean',
            ],
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    protected function messages()
    {
        return [
            'amount.required' => 'Le montant du taux est obligatoire.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'amount.min' => 'Le montant doit être supérieur ou égal à 0.',
        ];
    }

    /**
     * Initialiser le formulaire avec un taux existant
     */
    public function setRate(Rate $rate)
    {
        $this->rate = $rate;
        $this->amount = $rate->amount;
        $this->is_changed = $rate->is_changed ?? false;
    }

    /**
     * Créer un nouveau taux
     */
    public function store()
    {
        $this->validate();

        Rate::create([
            'amount' => $this->amount,
            'is_changed' => $this->is_changed ?? false,
            'school_id' => School::DEFAULT_SCHOOL_ID(),
        ]);

        $this->reset();
    }

    /**
     * Mettre à jour un taux existant
     */
    public function update()
    {
        $this->validate();

        $this->rate->update([
            'amount' => $this->amount,
            'is_changed' => $this->is_changed ?? false,
        ]);
    }
}
