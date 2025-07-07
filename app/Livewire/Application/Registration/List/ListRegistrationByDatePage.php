<?php

namespace App\Livewire\Application\Registration\List;

use App\Domain\Features\Registration\RegistrationFeature;
use App\Models\Registration;
use App\Models\Student;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListRegistrationByDatePage extends Component
{
    use WithPagination;
    protected $listeners = [
        'deletedStudentListner' => 'delete',
    ];
    public bool $isOld = false;
    public string $dateFilter = '';

    public int $per_page = 20;
    public int  $option_filter = 0;

    #[Url(as: 'q')]
    public $q = '';
    #[Url(as: 'sortBy')]
    public $sortBy = 'students.name';
    #[Url(as: 'sortAsc')]
    public $sortAsc = true;
    public ?Student $studentToDelete;
    public function sortData($value): void
    {
        if ($value == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $value;
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
    public function mount()
    {
        $this->dateFilter = date('Y-m-d');
    }
    public function render()
    {
        return view('livewire.application.registration.list.list-registration-by-date-page', [
            'registrations' => RegistrationFeature::getListOldOrNew(
                $this->dateFilter,
                null,
                null,
                $this->option_filter,
                null,
                null,
                $this->isOld,
                $this->q,
                $this->sortBy,
                $this->sortAsc,
                $this->per_page
            ),
            'count' => RegistrationFeature::getCount(
                $this->dateFilter,
                null,
                null,
                null,
                null,
                null,
                $this->isOld
            )
        ]);
    }
}
