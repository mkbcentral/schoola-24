<?php

namespace App\Livewire\Application\Registration\Form;

use App\Domain\Features\Payment\OtherPaymentFeature;
use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\RegistrationForm;
use App\Models\ResponsibleStudent;
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

    public function updatedFormOptionId($val)
    {
        $this->selectedOption = $val;
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
