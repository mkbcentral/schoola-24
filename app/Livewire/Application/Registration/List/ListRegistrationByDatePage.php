<?php

namespace App\Livewire\Application\Registration\List;

use App\Domain\Features\Registration\RegistrationFeature;
use App\Models\Registration;
use App\Models\Student;
use Livewire\Attributes\Url;
use Livewire\Component;

class ListRegistrationByDatePage extends Component
{
    protected $listeners = [
        'deletedStudentListner' => 'delete',
    ];
    public bool $isOld;
    public string $dateFilter;

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
    public function mount() {}
    public function render()
    {
        return view('livewire.application.registration.list.list-registration-by-date-page', [
            'registrations' => RegistrationFeature::getListOoldOrNew(
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
        ]);
    }
}
