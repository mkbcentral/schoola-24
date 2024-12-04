<?php

namespace App\Livewire\Application\Finance\Borrowing\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\MoneyBorrowingForm;
use App\Models\MoneyBorrowing;
use Exception;
use Livewire\Component;

class FormBorrowingPage extends Component
{
    protected $listeners = [
        'moneyBorrowingData' => 'getMoneyBorrowing',
        'initialBorrowingForm' => 'initBorrowingForm',
    ];
    public ?MoneyBorrowing $moneyBorrowing = null;
    public MoneyBorrowingForm $form;

    /**
     * @return void
     */
    public function initFormField(): void
    {
        $this->form->reset();
        $this->form->created_at = date('Y-m-d');
        $this->form->month = date('m');
    }
    public function initBorrowingForm(): void
    {
        $this->initFormField();
    }
    public function getMoneyBorrowing(MoneyBorrowing $moneyBorrowing): void
    {
        $this->moneyBorrowing = $moneyBorrowing;
        $this->form->fill($moneyBorrowing->toArray());
        $this->form->created_at = $moneyBorrowing->created_at->format('Y-m-d');
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
            $this->form->update($this->moneyBorrowing);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function handlerSubmit(): void
    {
        $this->validate();
        if ($this->moneyBorrowing == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->moneyBorrowing = null;
        $this->dispatch('moneyBorrowingListRefreshed');
        $this->initFormField();
    }
    public function cancelUpdate(): void
    {
        $this->moneyBorrowing = null;
        $this->initFormField();
    }
    public function mount(): void
    {
        $this->form->created_at = date('Y-m-d');
    }
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.finance.borrowing.form.form-borrowing-page');
    }
}
