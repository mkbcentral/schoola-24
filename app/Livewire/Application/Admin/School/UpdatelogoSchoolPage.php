<?php

namespace App\Livewire\Application\Admin\School;

use App\Domain\Utils\AppMessage;
use Exception;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdatelogoSchoolPage extends Component
{
    use WithFileUploads;

    public $logo;

    public function updatedLogo()
    {
        try {
            $path = $this->logo->store('school/logo', 'public');
            auth()->user()->school->update(['logo' => $path]);
            $this->dispatch('updated', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.application.admin.school.updatelogo-school-page');
    }
}
