<?php

namespace App\Livewire\Application\Student\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\RegistrationForm;
use App\Models\ClassRoom;
use App\Models\Registration;
use App\Models\Student;
use Exception;
use Livewire\Component;

class FormEditStudentPage extends Component
{
    protected $listeners = [
        'studentData' => 'getStudent',
    ];

    public ?Student $student = null;

    public ?Registration $registration = null;

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

    public function getStudent(?Student $student, Registration $registration)
    {
        $this->student = $student;
        $this->registration = $registration;
        $this->selectedOption = $this->student->registration->classRoom->option_id;
        $this->gender = $this->student->gender;
        $this->form->name = $this->student->name;
        $this->form->date_of_birth = $this->student->date_of_birth;
        $this->form->place_of_birth = $this->student->place_of_birth;
        $this->selectedOption = $registration->classRoom->option_id;
        $this->form->is_old = $registration->is_old;
        $this->form->registration_fee_id = $registration->registration_fee_id;
        $this->form->class_room_id = $registration->class_room_id;
        $this->isOldSelected = $registration->is_old;
        $this->form->created_at = $registration->created_at->format('Y-m-d');
    }

    public function update()
    {
        $fields = $this->validate();
        try {
            if ($this->gender == '') {
                $this->dispatch('error', ['message' => AppMessage::ACTION_FAILLED]);
            } else {
                $this->form->update($this->student, $this->gender, $this->registration);
                $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
                $this->dispatch('close-form-edit-student');
                $this->dispatch('refreshListStudent');
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.application.student.form.form-edit-student-page', [
            'classRooms' => ClassRoom::query()->where('option_id', $this->selectedOption)->get(),
        ]);
    }
}
