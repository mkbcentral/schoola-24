<?php

namespace App\Livewire\Application\Registration\Form;

use App\Domain\Features\Payment\OtherPaymentFeature;
use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\RegistrationForm;
use App\Models\RegistrationFee;
use App\Models\ResponsibleStudent;
use App\Models\School;
use App\Models\SchoolYear;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class NewRegistrationForm extends Component
{

    public $responsible_student_id;
    public ?ResponsibleStudent $responsibleStudent = null;
    public RegistrationForm $form;
    public $selectedOption = 0;
    public bool $isOldSelected = false;
    public string $gender = '';
    public   $responsibleStudentList = [];

    public function updatedSelectedOption($val)
    {
        if ($val > 0) {
            $registrationFee = RegistrationFee::query()
                ->join(
                    'category_registration_fees',
                    'registration_fees.category_registration_fee_id',
                    '=',
                    'category_registration_fees.id',
                )
                ->join('options', 'registration_fees.option_id', '=', 'options.id')
                ->join('sections', 'options.section_id', '=', 'sections.id')
                ->where('sections.school_id', School::DEFAULT_SCHOOL_ID())
                ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->where('options.id', $val)
                ->where('category_registration_fees.is_old', false)
                ->select('registration_fees.*')
                ->first();
            $this->form->registration_fee_id = $registrationFee->id;
        }
    }
    public function updatedResponsibleStudentId($value)
    {
        $this->responsibleStudent = ResponsibleStudent::find($value);
        if ($this->responsibleStudent) {
            $this->responsible_student_id = $this->responsibleStudent->id;
        }
    }
    public function save()
    {
        $data = $this->validate();
        try {
            if ($this->gender == '') {
                $this->dispatch('error', ['message' => AppMessage::ACTION_FAILLED]);
            }
            $registration = $this->form->create($this->responsibleStudent, $this->gender);
            if (!$registration) {
                $this->dispatch('error', ['message' => 'L\'élève existe déjà pour ce responsable.']);
                return;
            }
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
            $this->dispatch('close-form-student');
            OtherPaymentFeature::createPaymentForRegistration($registration);
            $this->reset(['form', 'responsible_student_id', 'responsibleStudent', 'selectedOption', 'isOldSelected', 'gender']);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function handlerSubmit()
    {
        $this->save();
        $this->dispatch('refreshListResponsibleStudent');
    }
    public function mount()
    {
        $this->form->created_at = date('Y-m-d');
        $this->responsibleStudentList = ResponsibleStudent::orderBy('name', 'asc')->get();
    }

    public function render()
    {

        return view('livewire.application.registration.form.new-registration-form');
    }
}
