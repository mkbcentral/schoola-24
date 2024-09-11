<?php

namespace App\Livewire\Application\Widgets\Student;

use App\Domain\Features\Registration\RegistrationFeature;
use App\Domain\Features\Student\StudentFeature;
use App\Domain\Helpers\RegistrationHelper;
use App\Domain\Utils\AppMessage;
use App\Models\Registration;
use App\Models\Student;
use Exception;;

use Livewire\Component;
use Livewire\WithPagination;

class ListStudentWidget extends Component
{
    use WithPagination;
    protected $listeners = [
        'refreshListStudentWidget' => '$refresh',
        'deletedStudentListner' => 'delete',
    ];
    public mixed $registrations;
    public ?Student $studentToDelete;

    /**
     * Editer un élève
     * @param \App\Models\Student $student
     * @return void
     */
    public function edit(Student $student)
    {
        $this->dispatch('studentData', $student);
    }
    /**
     * Ouvrir le formulaire pour marquer abandon
     * @param \App\Models\Registration $registration
     * @return void
     */
    public function openMakeGiveUpStudentFom(Registration $registration)
    {
        $this->dispatch('registrationData', $registration);
    }
    /**
     * Ouvrir le formulaire pour changer de classe
     * @param \App\Models\Registration $registration
     * @return void
     */
    public function changeClassStudent(Registration $registration)
    {
        $this->dispatch('registrationData', $registration);
    }

    /**
     * Ouvrir le formulaire passer un payement
     * @param mixed $registration
     * @return void
     */
    public function openPaymentForm(?Registration $registration)
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
        $registrations = Registration::all();
        foreach ($registrations as $registration) {
            $code =  RegistrationHelper::gerenateRegistrationCode($registration->class_room_id, rand(100, 1000));
            $registration->update(['code' => $code]);
        }
        $this->reset();
    }

    /**
     * Afficher la confirmation pour supprimer un élève
     * @param \App\Models\Student $student
     * @return void
     */
    public function showDeleteDialog(Student $student)
    {
        $this->studentToDelete = $student;
        $this->dispatch('delete-student-dialog', $student);
    }

    /**
     * Supprimer un élève
     * @return void
     */
    public function delete()
    {
        try {
            if ($this->studentToDelete->registration->payments->isEmpty()) {
                RegistrationFeature::delete($this->studentToDelete->registration);
                StudentFeature::delete($this->studentToDelete);
                $this->dispatch('student-deleted', ['message' => AppMessage::DATA_DELETED_SUCCESS]);
            } else {
                $this->dispatch('delete-student-failed', ['message' => AppMessage::DATA_DELETED_FAILLED . ", car l'élève a des données"]);
            }
        } catch (Exception $ex) {
            $this->dispatch('delete-student-failed', ['message' => $ex->getMessage()]);
        }
    }

    /**
     * Générer plusieurs qr-code pour plusieurs élèves séléctionnés
     * @return void
     */
    public function generateQrcodeItems(): void
    {
        try {
            $registrations = Registration::whereIn('id', $this->selectedRegistrations)->get();
            foreach ($registrations as $registration) {
                $qrcode = StudentFeature::generateStudentQRCode($registration);
                $registration->update(['qr_code' => $qrcode]);
            }
            $this->dispatch('added', ['message' => AppMessage::QRCODE_GENERATED_SUCCESSFULLY]);
        } catch (Exception $ex) {
            $this->dispatch('delete-student-failed', ['message' => $ex->getMessage()]);
        }
    }


    public function mount(mixed  $registrations)
    {
        $this->registrations = $registrations;
    }

    public function render()
    {
        return view('livewire.application.widgets.student.list-student-widget');
    }
}
