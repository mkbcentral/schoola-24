<?php

namespace App\Livewire\Application\Registration\List;

use App\Domain\Features\Registration\RegistrationFeature;
use App\Models\ClassRoom;
use App\Models\Student;
use Livewire\Attributes\Url;
use Livewire\Component;

class ListRegistrationByClassRoomPage extends Component
{
    public $classRoomId;
    #[Url(as: 'sortBy')]
    public $sortBy = 'students.name';
    #[Url(as: 'sortAsc')]
    public $sortAsc = true;

    #[Url(as: 'q')]
    public $q = '';

    public ?ClassRoom $classRoom;

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

    public function mount()
    {
        $this->classRoom = ClassRoom::find($this->classRoomId);
    }
    public function render()
    {
        return view('livewire.application.registration.list.list-registration-by-class-room-page', [
            'registrations' => RegistrationFeature::getList(
                null,
                null,
                null,
                null,
                $this->classRoomId,
                null,
                null,
                $this->q,
                $this->sortBy,
                $this->sortAsc,
                null

            ),
        ]);
    }
}
