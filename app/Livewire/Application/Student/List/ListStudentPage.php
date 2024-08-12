<?php

namespace App\Livewire\Application\Student\List;

use App\Domain\Features\Registration\RegistrationFeature;
use App\Domain\Features\Student\StudentFeature;
use App\Domain\Utils\AppMessage;
use App\Models\Registration;
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
        'deletedStudentListner' => 'delete',
    ];
    public int $per_page = 10, $option_filter = 0;
    #[Url(as: 'q')]
    public $q = '';
    #[Url(as: 'sortBy')]
    public $sortBy = 'students.name';
    #[Url(as: 'sortAsc')]
    public $sortAsc = true;

    public ?Student $studentToDelete;

    public array $selectedRegistrations = [];

    public function sortData($value): void
    {
        if ($value == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $value;
    }

    public function updatedOptionFilter($val): void
    {
        if ($val == null) {
            $this->option_filter = 0;
        }
    }

    public function edit(Student $student)
    {
        $this->dispatch('studentData', $student);
    }

    public function openMakeGiveUpStudentFom(Registration $registration)
    {
        $this->dispatch('registrationData', $registration);
    }

    public function changeClassStudent(Registration $registration)
    {
        $this->dispatch('registrationData', $registration);
    }

    public function openPaymentForm(?Registration $registration)
    {
        $this->dispatch('registrationData', $registration);
    }

    public function refreshData(): void
    {
        $this->reset();
    }

    public function showDeleteDialog(Student $student)
    {
        $this->studentToDelete = $student;
        $this->dispatch('delete-student-dialog', $student);
    }

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

    public function render()
    {
        return view('livewire.application.student.list.list-student-page', [
            'registrations' => RegistrationFeature::getList(
                null,
                null,
                null,
                $this->option_filter,
                null,
                null,
                null,
                $this->q,
                $this->sortBy,
                $this->sortAsc,
                $this->per_page

            ),
            'counter' => RegistrationFeature::getCount(
                null,
                null,
                null,
                null,
                null,
                null,
                null
            )
        ]);
    }
}
