<?php

namespace App\Livewire\Application\Admin\List;

use App\Models\School;
use Livewire\Component;

class ListSchoolPage extends Component
{
    public function render()
    {
        return view('livewire.application.admin.list.list-school-page', [
            'schoools' => School::all()
        ]);
    }
}
