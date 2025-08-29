<?php

namespace App\Livewire\Application\Payment\Reguralization\Form;

use App\Domain\Helpers\DateFormatHelper;
use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\FormPaymentRegularization;
use App\Models\CategoryFee;
use App\Models\Payment;
use App\Models\PaymentRegularization;
use App\Models\Registration;
use App\Models\ScolarFee;
use App\Models\Student;
use Exception;
use Livewire\Component;

class FormRegularizationPage extends Component
{

    protected $listeners = [
        "paymentRegularizationData" => "getPaymentRegularization",
        "paymentRegularizationFormReseted" => "clearnFormData",
    ];
    public ?PaymentRegularization $paymentRegularization = null;
    public FormPaymentRegularization $form;
    public int $selectedOptionId = 0;
    public $student_id = 0;
    public ?Student $student = null;
    public ?ScolarFee $scolarFee = null;
    public ?Registration $lastRegistration = null;

    public array $listMonths = [];

    //updated student_id
    public function updatedStudentId($val)
    {
        $this->student = Student::find($val);
        $this->form->name = $this->student->name;
    }
    public function updatedFormCategoryFeeId($val)
    {
        if ($this->student != null) {
            $months = collect(DateFormatHelper::getSchoolFrMonths())
                ->reject(fn($month) => in_array(strtoupper($month['name']), ['JUILLET', 'AOUT']))
                ->values()
                ->all();
            $paymentStatus = false;
            $this->lastRegistration = Registration::query()
                ->where('school_year_id', 1)
                ->where('student_id', $this->student->id)->first();
            foreach ($months as $month) {
                $paymentStatus = $this->lastRegistration->getStatusPayment(
                    $this->lastRegistration->id,
                    $val,
                    1,
                    $month['number'],
                );

                if ($paymentStatus == false) {
                    $this->listMonths[] = $month;
                }
            }
            $this->scolarFee = ScolarFee::query()
                ->where('category_fee_id', $val)
                ->where('class_room_id', $this->lastRegistration->class_room_id)
                ->first();
            $this->form->amount = $this->scolarFee ? $this->scolarFee->amount : 0;
            $this->form->class_room_id = $this->lastRegistration->class_room_id;
        } else {
            $this->dispatch('error', ['message' => 'Selectionnez un élève SVP !']);
        }
    }

    public function clearnFormData()
    {
        $this->paymentRegularization = null;
        $this->form->reset();
        $this->form->created_at = date('Y-m-d');
    }

    public function updatedFormOptionId($val)
    {
        $this->selectedOptionId = $val;
    }

    public function getPaymentRegularization(PaymentRegularization $paymentRegularization)
    {
        $this->paymentRegularization = $paymentRegularization;
        $this->form->created_at = $paymentRegularization->created_at->format('Y-m-d');
        $this->form->name = $paymentRegularization->name;
        $this->form->month = $paymentRegularization->month;
        $this->form->amount = $paymentRegularization->amount;
        $this->form->category_fee_id = $paymentRegularization->category_fee_id;
        $this->form->option_id = $paymentRegularization->classRoom->option_id;
        $this->selectedOptionId = $paymentRegularization->classRoom->option_id;
        $this->form->class_room_id = $paymentRegularization->class_room_id;
    }

    public function save()
    {
        $this->validate();
        try {
            if ($this->form->create()) {
                Payment::create([
                    'month' => $this->form->month,
                    'scolar_fee_id' => $this->lastRegistration->scolar_fee_id,
                    'registration_id' => $this->lastRegistration->id,
                    'created_at' => $this->form->created_at,
                    'is_paid' => true
                ]);
                $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function update()
    {
        $this->validate();
        try {
            $this->form->update($this->paymentRegularization);
            $this->dispatch('updated', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function handlerSubmit()
    {
        if ($this->paymentRegularization == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->dispatch('paymentRegularizationListRefreshed');
        $this->form->reset();
        $this->paymentRegularization = null;
        $this->form->created_at = date('Y-m-d');
    }

    public function mount()
    {
        $this->form->created_at = date('Y-m-d');
    }

    public function render()
    {
        return view('livewire.application.payment.reguralization.form.form-regularization-page', [
            'categoryFees' => CategoryFee::query()
                ->whereIn('id', [1, 2, 5, 14, 15])
                ->get(),
        ]);
    }
}
