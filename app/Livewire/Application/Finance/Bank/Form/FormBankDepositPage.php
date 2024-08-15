<?php

namespace App\Livewire\Application\Finance\Bank\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\BankDepositForm;
use App\Models\BankDeposit;
use Exception;
use Livewire\Component;

class FormBankDepositPage extends Component
{
    protected $listners = [
        'bankDepositData' => 'getBankDeposit'
    ];
    public ?BankDeposit $bankDeposit = null;
    public BankDepositForm $form;

    public function getBankDeposit(BankDeposit $bankDeposit): void
    {
        $this->bankDeposit = $bankDeposit;
        $this->form->reset();
    }
    public function save(): void
    {
        try {
            $this->form->create();
            $this->dispatch('added', ['message', AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message', $ex->getMessage()]);
        }
    }
    public function update(): void
    {
        try {
            $this->form->update($this->bankDeposit);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function handlerSubmit(): void
    {
        if ($this->bankDeposit == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->form->reset();
    }

    public function mount()
    {
        $this->form->created_at = date('Y-m-d');
    }

    public function render()
    {
        return view('livewire.application.finance.bank.form.form-bank-deposit-page');
    }
}
