<?php

namespace App\Livewire\Application\Student\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\RegistrationForm;
use App\Models\Student;
use Exception;
use Livewire\Component;

class FormEditStudentPage extends Component
{
    protected $listeners = [
        "studentData" => "getStudent",
    ];
    public ?Student $student = null;
    public RegistrationForm $form;

    public $selectedOption = 0;
    public string $gender = '';
    public bool $isOldSelected = false;

    public function updatedFormOptionId($val): void
    {
        $this->selectedOption = $val;
    }

    public function updatedFormIsOld($val)
    {
        $this->isOldSelected = $val;
    }


    public function getStudent(?Student $student)
    {
        $this->student = $student;
        $this->selectedOption = $this->student->registration->classRoom->option_id;
        $this->form->option_id = $this->selectedOption;
        $this->gender = $this->student->gender;
        $this->form->name = $this->student->name;
        $this->form->date_of_birth = $this->student->date_of_birth;
        $this->form->place_of_birth = $this->student->place_of_birth;
        $this->form->is_old = $this->student->registration->is_old;
        $this->form->registration_fee_id = $this->student->registration->registration_fee_id;
        $this->form->class_room_id = $this->student->registration->class_room_id;
        $this->isOldSelected = $this->student->registration->is_old;
        $this->form->created_at = $this->student->registration->created_at->format('Y-m-d');
    }


    public function update()
    {
        $this->validate();
        try {
            if ($this->gender == '') {
                $this->dispatch('error', ['message' => AppMessage::ACTION_FAILLED]);
            }
            $this->form->update($this->student, $this->gender);
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
            $this->dispatch('close-form-edit-student');
            $this->dispatch('refreshListStudent');
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.application.student.form.form-edit-student-page');
    }
}
