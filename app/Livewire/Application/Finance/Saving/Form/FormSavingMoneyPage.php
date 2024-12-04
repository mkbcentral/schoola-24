<?php

namespace App\Livewire\Application\Finance\Saving\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\SavingMoneyForm;
use App\Models\SavingMoney;
use Exception;
use Livewire\Component;

class FormSavingMoneyPage extends Component
{
    protected $listeners = [
        'savingMoneyData' => 'getSavingMoney',
        'initialSavingMoneyForm' => 'initSavingMoneyForm',
    ];
    public ?SavingMoney $savingMoney = null;
    public SavingMoneyForm $form;

    public function initSavingMoneyForm():void
    {
        $this->form->reset();
        $this->form->created_at = date('Y-m-d');
    }
    public function getSavingMoney(SavingMoney $savingMoney): void
    {
        $this->savingMoney = $savingMoney;
        $this->form->fill($savingMoney->toArray());
        $this->form->created_at = $savingMoney->created_at->format('Y-m-d');
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
            $this->form->update($this->savingMoney);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function handlerSubmit(): void
    {
        $this->validate();
        if ($this->savingMoney == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->form->reset();
        $this->savingMoney = null;
        $this->dispatch('savingMoneyListRefreshed');
        $this->form->created_at = date('Y-m-d');
    }

    public function cancelUpdate(): void
    {
        $this->savingMoney = null;
        $this->form->reset();
        $this->form->created_at = date('Y-m-d');
    }

    public function mount(): void
    {
        $this->form->created_at = date('Y-m-d');
        $this->form->month=date('m');
    }
    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.finance.saving.form.form-saving-money-page');
    }
}
