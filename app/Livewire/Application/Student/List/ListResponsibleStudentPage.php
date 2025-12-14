<?php

namespace App\Livewire\Application\Student\List;

use App\Domain\Features\Student\ResponsibleStudentFeature;
use App\Domain\Utils\AppMessage;
use App\Models\ResponsibleStudent;
use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListResponsibleStudentPage extends Component
{
    use WithPagination;

    protected $listeners = [
        'refreshListResponsibleStudent' => '$refresh',
        'deletedResponsibleStudentListner' => 'delete',
    ];

    public ?ResponsibleStudent $responsibleStudentToDelete = null;

    public int $per_page = 10;

    #[Url(as: 'q')]
    public $q = '';

    #[Url(as: 'sortBy')]
    public $sortBy = 'name';

    #[Url(as: 'sortAsc')]
    public $sortAsc = true;

    public function sortData($value): void
    {
        if ($value == $this->sortBy) {
            $this->sortAsc = ! $this->sortAsc;
        }
        $this->sortBy = $value;
    }

    public function openNewResponsibleStudent()
    {
        $this->dispatch('dataFormResed');
    }

    public function edit(ResponsibleStudent $responsibleStudent)
    {
        $this->dispatch('responsibleStudentData', $responsibleStudent);
    }

    public function addNewInscription(ResponsibleStudent $responsibleStudent)
    {
        $this->dispatch('responsibleStudentDataOnReg', $responsibleStudent);
        $this->dispatch('open-form-student');
    }

    public function getListStudent(ResponsibleStudent $responsibleStudent)
    {
        $this->dispatch('studentByResponsible', $responsibleStudent);
        $this->dispatch('open-list-student-by-responsible');
    }

    public function showDeleteDialog(ResponsibleStudent $responsibleStudent)
    {
        $this->responsibleStudentToDelete = $responsibleStudent;
        $this->dispatch('delete-responsibleStudent-dialog', $responsibleStudent);
    }

    public function refreshData(): void
    {
        $this->reset();
    }

    public function delete()
    {
        try {
            if ($this->responsibleStudentToDelete->students->isEmpty()) {
                ResponsibleStudentFeature::delete($this->responsibleStudentToDelete);
                $this->dispatch('responsibleStudent-deleted', ['message' => AppMessage::DATA_DELETED_SUCCESS]);
            } else {
                $this->dispatch('delete-responsibleStudent-failed', ['message' => AppMessage::DATA_DELETED_FAILLED . ', car le responsable a des données']);
            }
        } catch (Exception $ex) {
            $this->dispatch('delete-responsibleStudent-failed', ['message' => $ex->getMessage()]);
        }
    }

    public function mount() {}

    public function render()
    {
        return view('livewire.application.student.list.list-responsible-student-page', [
            'responsibleStudents' => ResponsibleStudentFeature::getList(
                $this->q,
                $this->sortBy,
                $this->sortAsc,
                $this->per_page
            ),
        ]);
    }
}
