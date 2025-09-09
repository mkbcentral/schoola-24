<?php

namespace App\Livewire\Application\Student\List;

use App\Domain\Features\Registration\RegistrationFeature;
use App\Domain\Features\Student\StudentFeature;
use App\Domain\Utils\AppMessage;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\SmsPayment;
use App\Models\Student;
use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;


class ListStudentPage extends Component
{
    use WithPagination;
    protected $listeners = [
        'refreshListStudent' => '$refresh',
    ];
    public int $per_page = 10;
    public int  $option_filter = 0;
    public int  $class_room_filter = 0;
    public int $selectedOptionId = 0;
    public ?bool $isOld = null;
    #[Url(as: 'q')]
    public $q = '';
    #[Url(as: 'sortBy')]
    public $sortBy = 'registrations.code';
    #[Url(as: 'sortAsc')]
    public $sortAsc = true;
    public ?Student $studentToDelete;
    public  $selectedRegistrations = [];
    public bool $selectPageRows = false;

    /**
     * Ouvrir le modal de création de dérogation pour une inscription
     */
    public function openDerogationModal(Registration $registration): void
    {
        $this->dispatch('openDerogationModal', $registration);
    }

    /**
     * Trier le manière (ASC/DESC)
     * @param mixed $value
     * @return void
     */
    public function sortData(mixed $value): void
    {
        if ($value == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $value;
        $this->resetPage();
    }


    /**
     * Selectionner toutes les ligne de du table
     * @param mixed $value
     * @return void
     */
    public function updatedSelectPageRows(mixed $value): void
    {
        if ($value) {
            $this->per_page = 1000;
            $this->selectedRegistrations = $this->registrations
                ->pluck('id')->map(function ($id) {
                    return (string)$id;
                });
        } else {
            $this->reset(['selectedRegistrations', 'selectPageRows']);
            $this->per_page = 10;
        }
    }

    public function updatedOptionFilter($val): void
    {
        if ($val == null) {
            $this->option_filter = 0;
        }
        $this->selectedOptionId = $val;
    }

    /**
     * Editer un élève
     * @param \App\Models\Student $student
     * @return void
     */
    public function edit(Student $student, Registration $registration): void
    {
        $this->dispatch('studentData', $student, $registration);
    }
    /**
     * Ouvrir le formulaire pour marquer abandon
     * @param \App\Models\Registration $registration
     * @return void
     */
    public function openMakeGiveUpStudentFom(Registration $registration): void
    {
        $this->dispatch('registrationData', $registration);
    }
    /**
     * Ouvrir le formulaire pour changer de classe
     * @param \App\Models\Registration $registration
     * @return void
     */
    public function changeClassStudent(Registration $registration): void
    {
        $this->dispatch('registrationData', $registration);
    }

    /**
     * Ouvrir le formulaire passer un payement
     * @param mixed $registration
     * @return void
     */
    public function openPaymentForm(?Registration $registration): void
    {
        $this->dispatch('registrationData', $registration);
    }

    /**
     * Générer ou régeénerer un un qr-code pour un élève
     * @param \App\Models\Registration $registration
     * @return void
     */
    public function generateQRCode(Registration $registration): void
    {
        try {
            $qrcode = StudentFeature::generateStudentQRCode($registration);
            $registration->update(['qr_code' => $qrcode]);
            $this->dispatch('added', ['message' => AppMessage::QRCODE_GENERATED_SUCCESSFULLY]);
        } catch (Exception $ex) {
            $this->dispatch('delete-student-failed', ['message' => $ex->getMessage()]);
        }
    }
    /**
     * Actualiser la liste
     * @return void
     */
    public function refreshData(): void
    {
        $this->reset();
        $this->resetPage();
    }


    /**
     * Supprimer un élève
     * @return void
     */
    public function delete(Registration $registration): void
    {
        try {
            $payments = Payment::where('registration_id', $registration->id)->get();
            foreach ($payments as $p) {
                SmsPayment::where('payment_id', $p->id)->delete();
                $p->delete();
            }
            $student = $registration->student;
            $registration->delete();
            if ($student) {
                $student->delete();
            }
            $this->dispatch('info', ['message' => AppMessage::DATA_DELETED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    /**
     * Générer plusieurs qr-code pour plusieurs élèves séléctionnés
     * @return void
     */
    public function generateQrcodeItems(): void
    {
        try {
            RegistrationFeature::generateQRCodes($this->selectedRegistrations);
            $this->dispatch('added', ['message' => AppMessage::QRCODE_GENERATED_SUCCESSFULLY]);
        } catch (Exception $ex) {
            $this->dispatch('delete-student-failed', ['message' => $ex->getMessage()]);
        }
    }

    public function mekeIsOld()
    {
        $this->isOld = true;
    }
    public function mekeIsNew()
    {
        $this->isOld = false;
    }



    /**
     * Fonction magique (computed) qui permet d'avoir les regisations dans tout les composant
     * @return mixed
     */
    public function getRegistrationsProperty(): mixed
    {
        return RegistrationFeature::getList(
            null,
            null,
            null,
            $this->option_filter,
            $this->class_room_filter,
            null,
            $this->q,
            $this->sortBy,
            $this->sortAsc,
            $this->isOld,
            $this->per_page,


        );
    }

    /**
     * Marquer ou démarcher une inscription comme exemptée de frais
     */
    public function toggleFeeExempted(Registration $registration): void
    {
        $registration->markFeeExempted(!$registration->is_fee_exempted);
        $this->dispatch('added', ['message' => $registration->is_fee_exempted ? 'Inscription marquée comme exemptée de frais.' : 'Exemption de frais retirée.']);
    }

    public function mount() {}

    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.student.list.list-student-page', [
            'registrations' => $this->registrations,
            'counter' => RegistrationFeature::getCountAll(
                null,
                null,
                null,
                $this->option_filter,
                $this->class_room_filter,
                null,
            )
        ]);
    }
}
