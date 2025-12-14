<?php

namespace App\Livewire\Application\V2\Registration\Form;

use App\DTOs\Registration\CreateRegistrationDTO;
use App\Livewire\Forms\RegisterExistingStudentFormValidator;
use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\RegistrationFee;
use App\Models\Section;
use App\Models\Student;
use App\Services\Registration\RegistrationService;
use Livewire\Component;

class RegisterExistingStudentForm extends Component
{
    public RegisterExistingStudentFormValidator $form;

    public $showModal = false;
    public $createdAt;

    // Pour afficher les infos de l'élève sélectionné
    public $selectedStudent = null;
    public $lastClassRoom = '';

    protected $listeners = [
        'openRegisterExistingStudent' => 'openModal',
    ];

    private RegistrationService $registrationService;

    public function boot(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function mount()
    {
        $this->createdAt = date('Y-m-d');
        $this->form->registrationDate = $this->createdAt;
    }

    public function updatedFormStudentId($value)
    {
        if ($value) {
            $this->selectedStudent = Student::with('registrations.classRoom')->find($value);
            if ($this->selectedStudent) {
                $this->lastClassRoom = $this->selectedStudent->getLastClassRoomName();
            }
        }
    }

    public function updatedFormSectionId($value)
    {
        $this->form->optionId = null;
        $this->form->classRoomId = null;
        $this->form->registrationFeeId = null;
    }

    public function updatedFormOptionId($value)
    {
        $this->form->classRoomId = null;

        // Auto-sélectionner les frais d'inscription pour anciens élèves
        if ($value) {
            $fee = RegistrationFee::query()
                ->join('category_registration_fees', 'registration_fees.category_registration_fee_id', '=', 'category_registration_fees.id')
                ->where('registration_fees.option_id', $value)
                ->where('category_registration_fees.is_old', true)
                ->select('registration_fees.*')
                ->first();

            if ($fee) {
                $this->form->registrationFeeId = $fee->id;
            }
        }
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->form->reset();
        $this->reset(['selectedStudent', 'lastClassRoom']);
        $this->createdAt = date('Y-m-d');
        $this->form->registrationDate = $this->createdAt;
        $this->resetValidation();
    }

    public function register()
    {
        try {
            $this->form->validateRegistration();

            // Vérifier si l'élève n'est pas déjà inscrit
            if ($this->registrationService->isStudentRegistered($this->form->studentId)) {
                $this->addError('form.studentId', 'Cet élève est déjà inscrit pour cette année scolaire.');
                return;
            }

            // Mettre à jour la date dans le form


            $registrationData = $this->form->getRegistrationData();
            $dto = CreateRegistrationDTO::fromArray($registrationData);

            $this->registrationService->registerExistingStudent($dto);

            $this->dispatch('added', message: 'Ancien élève inscrit avec succès!');
            $this->dispatch('registration-created');
            $this->closeModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Erreur lors de l\'inscription: ' . $e->getMessage());
            \Log::error('Erreur inscription ancien élève', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function render()
    {
        $students = Student::query()
            ->orderBy('name')
            ->get();

        $sections = Section::orderBy('name')->get();

        $options = Option::query()
            ->when($this->form->sectionId, fn($q) => $q->where('section_id', $this->form->sectionId))
            ->orderBy('name')
            ->get();

        $classRooms = ClassRoom::query()
            ->when($this->form->optionId, fn($q) => $q->where('option_id', $this->form->optionId))
            ->orderBy('name')
            ->get();

        return view('livewire.application.v2.registration.form.register-existing-student-form', [
            'students' => $students,
            'sections' => $sections,
            'options' => $options,
            'classRooms' => $classRooms,
        ]);
    }
}
