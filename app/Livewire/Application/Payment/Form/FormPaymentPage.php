<?php

namespace App\Livewire\Application\Payment\Form;

use App\Domain\Helpers\DateFormatHelper;
use App\Livewire\Forms\PaymentForm;
use App\Models\Registration;
use App\Models\ScolarFee;
use App\Services\Student\StudentDebtTrackerService;
use Livewire\Component;

class FormPaymentPage extends Component
{
    protected $listeners = [
        'registrationData' => 'getRegistration',
    ];

    public ?Registration $registration;

    public $selectedCategoryFeeId;

    public $selectedIdClassRoom = 0;

    public ?ScolarFee $scolarFee = null;

    public PaymentForm $form;

    public ?Registration $lastRegistration;

    // Pour la case à cocher "Payer immédiatement"
    public bool $is_ipaid = false;

    public function initFormFields(): void
    {
        $this->form->reset();
        $this->form->created_at = date('Y-m-d');
        $this->form->month = date('m');
        $this->is_ipaid = false;
    }

    public function updatedFormCategoryFeeId($val): void
    {
        $this->selectedCategoryFeeId = $val;
        $this->scolarFee = ScolarFee::query()
            ->where('category_fee_id', $val)
            ->where('class_room_id', $this->registration->class_room_id)
            ->first();
    }

    public function getRegistration(?Registration $registration): void
    {
        $this->registration = $registration;
        $this->selectedIdClassRoom = $registration->classRoom->id;
        $this->initFormFields();
        $this->lastRegistration = Registration::query()
            ->where('school_year_id', 1)
            ->where('student_id', $registration->student_id)->first();
    }

    public function save(): void
    {
        $this->validate();
        // Convertir le numéro du mois en label (ex: '10' => 'OCTOBRE')
        $monthLabel = DateFormatHelper::getMonthLabelFromNumber($this->form->month);
        $result = (new StudentDebtTrackerService)->payForMonth(
            $this->registration->id,
            $this->selectedCategoryFeeId,
            $monthLabel,
            [
                'created_at' => $this->form->created_at,
                'is_paid' => $this->is_ipaid,
            ]
        );
        if (! $result['success']) {
            $this->dispatch('error', ['message' => $result['message'] ?? 'Error processing payment']);
        } else {
            $this->dispatch('added', ['message' => $result]);
        }
        $this->dispatch('refreshPaymentList');
        $this->initFormFields();
        $this->is_ipaid = false;
    }

    public function mount(): void
    {
        $this->form->created_at = date('Y-m-d');
        $this->form->month = date('m');
        $this->is_ipaid = false;
    }

    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.payment.form.form-payment-page');
    }
}
