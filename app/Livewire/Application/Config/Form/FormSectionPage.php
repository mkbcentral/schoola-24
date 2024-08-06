<?php

namespace App\Livewire\Application\Config\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\SectionForm;
use App\Models\Section;
use Exception;
use Livewire\Component;

class FormSectionPage extends Component
{
    protected $listeners = ['sectionData' => 'getSection'];
    public ?Section $sectionSelected = null;

    public SectionForm $form;

    public function getSection(Section $section)
    {
        $this->sectionSelected = $section;
        $this->form->fill($this->sectionSelected->toArray());
    }

    public function save()
    {
        $input = $this->validate();
        try {
            $this->form->create($input);
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function update()
    {
        $input = $this->validate();
        try {
            $this->form->update($this->sectionSelected, $input);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function handlerSubmit()
    {
        if ($this->sectionSelected == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->form->name = '';
        $this->dispatch('sectionDataRefreshed');
    }

    public function cancelUpdate()
    {
        $this->sectionSelected = null;
        $this->form->name = '';
    }


    public function render()
    {
        return view('livewire.application.config.form.form-section-page');
    }
}
