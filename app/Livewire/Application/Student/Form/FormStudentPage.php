<?php

namespace App\Livewire\Application\Student\Form;

use App\Domain\Features\Payment\OtherPaymentFeature;
use App\Domain\Utils\AppMessage;
use App\Events\RegistrationCreatedEvent;
use App\Livewire\Forms\RegistrationForm;
use App\Models\ResponsibleStudent;
use Exception;
use Livewire\Component;

class FormStudentPage extends Component
{
    public ?ResponsibleStudent $responsibleStudent = null;
    public RegistrationForm $form;
    public $selectedOption = 0;
    public bool $isOldSelected = false;
    public string $gender = '';
    protected $listeners = [
        "responsibleStudentDataOnReg" => "getResponsibleStudent",
    ];
    public function updatedFormOptionId($val)
    {
        $this->selectedOption = $val;
    }

    public function updatedFormIsOld($val)
    {
        $this->isOldSelected = $val;
    }


    public function getResponsibleStudent(?ResponsibleStudent $responsibleStudent)
    {
        $this->responsibleStudent = $responsibleStudent;
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
    }


    public function render()
    {
        return view('livewire.application.student.form.form-student-page');
    }
}
