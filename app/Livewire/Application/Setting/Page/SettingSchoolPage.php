<?php

namespace App\Livewire\Application\Setting\Page;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\SchoolForm;
use Auth;
use Livewire\Component;

class SettingSchoolPage extends Component
{
    public SchoolForm $form;

    public function mount()
    {
        $this->form->fill(Auth::user()->school->toArray());
    }

    //handler update
    public function update()
    {
        $this->validate();
        $this->form->update(Auth::user()->school);
        $this->dispatch('updated', ['message', AppMessage::DATA_UPDATED_SUCCESS]);
    }
    public function render()
    {
        return view('livewire.application.setting.page.setting-school-page');
    }
}
