<?php

namespace App\Livewire\Application\Admin\List;

use App\Models\School;
use Livewire\Component;

class ListSchoolPage extends Component
{

    protected $listeners = [
        'listSchoolRefred' => '$refresh',
    ];

    public function openFormSchoolModal(): void
    {
        $this->dispatch('resetFromData');
    }
    public function edit(?School $school)
    {
        $this->dispatch('schoolData', $school);
    }

    public function render()
    {
        return view('livewire.application.admin.list.list-school-page', [
            'schoools' => School::all()
        ]);
    }
}
