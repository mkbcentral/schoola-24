<?php

namespace App\Livewire\Application\Student\List;

use App\Models\ResponsibleStudent;
use Livewire\Component;

class ListResposibleAutoComplete extends Component
{
    public function render()
    {
        return view('livewire.application.student.list.list-resposible-auto-complete', [
            'responsibleStudents' => ResponsibleStudent::orderBy('name', 'asc')->get(),
        ]);
    }
}
