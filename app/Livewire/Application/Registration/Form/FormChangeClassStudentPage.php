<?php

namespace App\Livewire\Application\Registration\Form;

use App\Domain\Utils\AppMessage;
use App\Models\ChangeClassStudent;
use App\Models\ClassRoom;
use App\Models\Registration;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Component;

class FormChangeClassStudentPage extends Component
{
    protected $listeners = [
        "registrationData" => "getRegistration",
    ];

    public Registration $registration;
    public Collection $listClassRoom;
    public int $class_room_id;


    public function getRegistration(Registration $registration)
    {
        $this->registration = $registration;
        $this->listClassRoom = ClassRoom::query()
            ->where('option_id', $this->registration->classRoom->option->id)
            ->whereNot('id', $this->registration->classRoom->id)
            ->get();
    }

    public function changeStudentClass()
    {
        $this->validate([
            'class_room_id' => 'required'
        ]);
        try {
            ChangeClassStudent::create([
                'registration_id' => $this->registration->id,
                'class_room_id' => $this->class_room_id
            ]);
            $this->registration->class_changed = true;
            $this->registration->update();
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
            $this->dispatch('refreshListStudent');
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.application.registration.form.form-change-class-student-page');
    }
}
