<?php

namespace App\Livewire\Application\Student\List;

use App\Domain\Features\Registration\RegistrationFeature;
use App\Models\ResponsibleStudent;
use Livewire\Attributes\Url;
use Livewire\Component;

class ListStudentByResponsiblePage extends Component
{
    protected $listeners = [
        "studentByResponsible" => "getResponsibleStudent",
    ];
    #[Url(as: 'sortBy')]
    public $sortBy = 'students.name';
    #[Url(as: 'sortAsc')]
    public $sortAsc = true;
    public ?ResponsibleStudent $responsibleStudent = null;
    public int $selectedResponsibleId = 0;

    public function getResponsibleStudent(?ResponsibleStudent $responsibleStudent)
    {
        $this->responsibleStudent = $responsibleStudent;
        $this->selectedResponsibleId = $responsibleStudent->id;
    }
    public function render()
    {

        return view('livewire.application.student.list.list-student-by-responsible-page', [
            'registrations' => RegistrationFeature::getList(
                null,
                null,
                null,
                null,
                null,
                $this->selectedResponsibleId,
                null,
                $this->sortBy,
                $this->sortAsc,
                null,
                100
            ),
        ]);
    }
}
