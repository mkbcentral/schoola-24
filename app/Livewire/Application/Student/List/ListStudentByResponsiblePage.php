<?php

namespace App\Livewire\Application\Student\List;

use App\Domain\Features\Registration\RegistrationFeature;
use App\Models\ResponsibleStudent;
use Livewire\Component;

class ListStudentByResponsiblePage extends Component
{
    protected $listeners = [
        "studentByResponsible" => "getResponsibleStudent",
    ];
    public ?ResponsibleStudent $responsibleStudent = null;

    public function getResponsibleStudent(?ResponsibleStudent $responsibleStudent)
    {
        $this->responsibleStudent = $responsibleStudent;
    }


    public function render()
    {

        return view('livewire.application.student.list.list-student-by-responsible-page', [
            'registrations' => RegistrationFeature::getListByResponsible(
                $this->responsibleStudent
            )
        ]);
    }
}
