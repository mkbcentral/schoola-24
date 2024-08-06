<?php

namespace App\Livewire\Application\Admin\List;

use App\Livewire\Forms\SchoolForm;
use App\Models\School;
use Livewire\Component;

class ConfigureSchoolPage extends Component
{
    public School $school;
    public SchoolForm $form;
    public function mount()
    {
        $this->form->fill($this->school->toArray());
    }
    public function render()
    {
        return view('livewire.application.admin.list.configure-school-page');
    }
}
