<?php

namespace App\Livewire\Application\V2\Registration\Form;

use App\DTOs\Registration\CreateRegistrationDTO;
use App\DTOs\Registration\CreateStudentDTO;
use App\Livewire\Forms\RegisterNewStudentForm as RegisterNewStudentFormValidator;
use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\RegistrationFee;
use App\Models\ResponsibleStudent;
use App\Models\Section;
use App\Services\Registration\RegistrationService;
use Livewire\Component;

class RegisterNewStudentForm extends Component
{
    public RegisterNewStudentFormValidator $form;

    public $showOffcanvas = false;
    public $createdAt;

    protected $listeners = [
        'openRegisterNewStudent' => 'openOffcanvas',
    ];

    private RegistrationService $registrationService;

    public function boot(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function mount()
    {
        $this->createdAt = date('Y-m-d');
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

        // Auto-sélectionner les frais d'inscription pour nouveaux élèves
        if ($value) {
            $fee = RegistrationFee::query()
                ->join('category_registration_fees', 'registration_fees.category_registration_fee_id', '=', 'category_registration_fees.id')
                ->where('registration_fees.option_id', $value)
                ->where('category_registration_fees.is_old', false)
                ->select('registration_fees.*')
                ->first();

            if ($fee) {
                $this->form->registrationFeeId = $fee->id;
            }
        }
    }

    public function openOffcanvas()
    {
        $this->showOffcanvas = true;
        $this->resetForm();
    }

    public function closeOffcanvas()
    {
        $this->showOffcanvas = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->form->reset();
        $this->createdAt = date('Y-m-d');
        $this->resetValidation();
    }

    public function nextStep()
    {
        try {
            $this->form->validateStep1();
            $this->form->currentStep = 2;
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Relancer l'exception pour afficher les erreurs
            throw $e;
        }
    }

    public function previousStep()
    {
        $this->form->currentStep = 1;
    }

    public function getSelectedResponsibleProperty()
    {
        if ($this->form->responsibleStudentId) {
            return ResponsibleStudent::find($this->form->responsibleStudentId);
        }
        return null;
    }

    public function register()
    {
        try {
            // Valider l'étape 2
            $this->form->validateStep2();

            // Préparer les données de l'élève
            $studentData = $this->form->getStudentData();

            // Préparer les données d'inscription
            $registrationData = [
                'class_room_id' => $this->form->classRoomId,
                'registration_fee_id' => $this->form->registrationFeeId,
                'rate_id' => \App\Models\Rate::DEFAULT_RATE_ID(),
                'is_old' => false,
                'created_at' => $this->createdAt,
            ];

            // Créer les DTOs
            $studentDTO = CreateStudentDTO::fromArray($studentData);
            $registrationDTO = CreateRegistrationDTO::fromArray($registrationData);

            // Enregistrer
            $this->registrationService->registerNewStudent($studentDTO, $registrationDTO);

            $this->dispatch('added', message: 'Nouvel élève inscrit avec succès!');
            $this->dispatch('registration-created');
            $this->closeOffcanvas();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Relancer l'exception de validation pour afficher les erreurs
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Erreur lors de l\'inscription: ' . $e->getMessage());
            \Log::error('Erreur inscription nouvel élève', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'form_data' => [
                    'name' => $this->form->name,
                    'gender' => $this->form->gender,
                    'classRoomId' => $this->form->classRoomId,
                    'registrationFeeId' => $this->form->registrationFeeId,
                ]
            ]);
        }
    }

    public function render()
    {
        $sections = Section::orderBy('name')->get();

        $options = Option::query()
            ->when($this->form->sectionId, fn($q) => $q->where('section_id', $this->form->sectionId))
            ->orderBy('name')
            ->get();

        $classRooms = ClassRoom::query()
            ->when($this->form->optionId, fn($q) => $q->where('option_id', $this->form->optionId))
            ->orderBy('name')
            ->get();

        $responsibleStudents = ResponsibleStudent::orderBy('name')->get();

        return view('livewire.application.v2.registration.form.register-new-student-form', [
            'sections' => $sections,
            'options' => $options,
            'classRooms' => $classRooms,
            'responsibleStudents' => $responsibleStudents,
        ]);
    }
}
