<?php

namespace App\Livewire\Application\V2\Registration\Form;

use App\DTOs\Registration\UpdateRegistrationDTO;
use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\Section;
use App\Services\Registration\RegistrationService;
use Livewire\Component;

class ChangeClassForm extends Component
{
    public $showModal = false;
    public $registrationId = null;
    public $registration = null;
    public $sectionId = null;
    public $optionId = null;
    public $newClassRoomId = null;

    protected $rules = [
        'newClassRoomId' => 'required|exists:class_rooms,id|different:registration.class_room_id',
    ];

    protected $messages = [
        'newClassRoomId.required' => 'Veuillez sélectionner une nouvelle classe',
        'newClassRoomId.different' => 'Veuillez sélectionner une classe différente de l\'actuelle',
    ];

    protected $listeners = [
        'openChangeClass' => 'openModal',
    ];

    private RegistrationService $registrationService;

    public function boot(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function updatedSectionId()
    {
        $this->optionId = null;
        $this->newClassRoomId = null;
    }

    public function updatedOptionId()
    {
        $this->newClassRoomId = null;
    }

    public function openModal($registrationId)
    {
        $this->registrationId = $registrationId;
        $this->registration = $this->registrationService->findById($registrationId);

        if ($this->registration) {
            $this->sectionId = $this->registration->classRoom->option->section_id;
            $this->optionId = $this->registration->classRoom->option_id;
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['registrationId', 'registration', 'sectionId', 'optionId', 'newClassRoomId']);
        $this->resetValidation();
    }

    public function changeClass()
    {
        $this->validate();

        try {
            $this->registrationService->changeClass($this->registrationId, $this->newClassRoomId);

            $this->dispatch('success', message: 'Classe changée avec succès!');
            $this->dispatch('registration-updated');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }

    public function render()
    {
        $sections = Section::orderBy('name')->get();

        $options = Option::query()
            ->when($this->sectionId, fn($q) => $q->where('section_id', $this->sectionId))
            ->orderBy('name')
            ->get();

        $classRooms = ClassRoom::query()
            ->when($this->optionId, fn($q) => $q->where('option_id', $this->optionId))
            ->orderBy('name')
            ->get();

        return view('livewire.application.v2.registration.form.change-class-form', [
            'sections' => $sections,
            'options' => $options,
            'classRooms' => $classRooms,
        ]);
    }
}
