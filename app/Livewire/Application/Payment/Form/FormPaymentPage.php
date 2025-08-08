<?php

namespace App\Livewire\Application\Payment\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\PaymentForm;
use App\Models\CategoryFee;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\SchoolYear;
use App\Models\ScolarFee;
use Exception;
use Livewire\Component;

class FormPaymentPage extends Component
{
    protected $listeners = [
        "registrationData" => "getRegistration",
    ];
    public ?Registration $registration;
    public $selectedCategoryFeeId, $selectedIdClassRoom = 0;
    public ?ScolarFee $scolarFee = null;
    public PaymentForm $form;
    public ?Registration $lastRegistration;


    /**
     * @return void
     */
    public function initFormFields(): void
    {
        $this->form->reset();
        $this->form->created_at = date('Y-m-d');
        $this->form->month = date('m');
    }

    /**
     * @param $val
     * @return void
     */
    public function updatedFormCategoryFeeId($val): void
    {
        $this->selectedCategoryFeeId = $val;
        $this->scolarFee = ScolarFee::query()
            ->where('category_fee_id', $val)
            ->where('class_room_id', $this->registration->class_room_id)
            ->first();
    }

    /**
     * @param Registration|null $registration
     * @return void
     */
    public function getRegistration(?Registration $registration): void
    {
        $this->registration = $registration;
        $this->selectedIdClassRoom = $registration->classRoom->id;
        $this->initFormFields();
        //$lastSChoolyear = SchoolYear::query()->where('is_last_year', true)->first();
        $this->lastRegistration = Registration::query()
            ->where('school_year_id', 1)
            ->where('student_id', $registration->student_id)->first();
        ///dd($this->lastRegistration);
    }
    public function save(): void
    {
        $this->validate();
        try {

            $this->form->create($this->registration->id, $this->scolarFee);
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
            $this->dispatch('refreshPaymentList');
            $this->initFormFields();
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function mount(): void
    {
        $this->form->created_at = date('Y-m-d');
        $this->form->month = date('m');
    }


    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.payment.form.form-payment-page');
    }
}
