<?php

namespace App\Livewire\Application\Admin\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\SchoolForm;
use App\Models\School;
use Exception;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class FormSchoolPage extends Component
{
    use WithFileUploads;
    protected $listeners = [
        'schoolData' => 'getSchool',
        'resetFromData' => 'cancelUpdate',
    ];

    public ?School $school = null;
    public SchoolForm $form;
    public function getSchool(School $school): void
    {
        $this->school = $school;
        $this->form->fill($school->toArray());
    }
    public function save(): void
    {
        try {
            $this->form->create();
            $this->dispatch('added', ['message', AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message', $ex->getMessage()]);
        }
    }
    public function update(): void
    {
        try {
            $this->form->update($this->school);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function handlerSubmit(): void
    {
        $this->validate();
        if ($this->school == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->form->reset();
        $this->school = null;
        $this->dispatch('listSchoolRefred');
        $this->dispatch('schoolConfigDataRefreshed');
    }
    public function cancelUpdate(): void
    {
        $this->school = null;
        $this->form->reset();
    }
    public function render()
    {
        return view('livewire.application.admin.form.form-school-page');
    }
}
