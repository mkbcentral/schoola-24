<?php

namespace App\Livewire\Application\Finance\Bank\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\BankDepositForm;
use App\Models\BankDeposit;
use Exception;
use Livewire\Component;

class FormBankDepositPage extends Component
{
    protected $listeners = [
        'bankDepositData' => 'getBankDeposit',
        'initialFormBankDeposit' => 'initBankDepositForm',
    ];

    public ?BankDeposit $bankDeposit = null;

    public BankDepositForm $form;

    public function initFormField(): void
    {
        $this->form->created_at = date('Y-m-d');
        $this->form->month = date('m');
    }

    public function initBankDepositForm(): void
    {
        $this->initFormField();
    }

    public function getBankDeposit(BankDeposit $bankDeposit): void
    {
        $this->bankDeposit = $bankDeposit;
        $this->form->fill($bankDeposit->toArray());
        $this->form->created_at = $bankDeposit->created_at->format('Y-m-d');
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
        $this->validate();
        if ($this->bankDeposit == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->form->reset();
        $this->bankDeposit = null;
        $this->dispatch('bankDepositListRefreshed');
        $this->initFormField();
    }

    public function cancelUpdate(): void
    {
        $this->bankDeposit = null;
        $this->form->reset();
        $this->initFormField();
    }

    public function mount()
    {
        $this->initFormField();
    }

    public function render()
    {
        return view('livewire.application.finance.bank.form.form-bank-deposit-page');
    }
}
