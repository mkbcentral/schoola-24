<?php

namespace App\Livewire\Application\Navigation\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\SubLinkForm;
use App\Models\SubLink;
use Exception;
use Livewire\Component;

class FormSubLinkPage extends Component
{
    protected $listeners = [
        'subLinkData' => 'getSubLink',
    ];

    public ?SubLink $subLink = null;

    public SubLinkForm $form;

    public function getSubLink(SubLink $subLink): void
    {
        $this->subLink = $subLink;
        $this->form->fill($subLink->toArray());
    }

    public function save(): void
    {
        try {
            $this->form->create();
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function update(): void
    {
        try {
            $this->form->update($this->subLink);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function handlerSubmit(): void
    {
        $this->validate();
        if ($this->subLink == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->form->reset();
        $this->subLink = null;
        $this->dispatch('subLinkListRefred');
    }

    public function cancelUpdate(): void
    {
        $this->subLink = null;
        $this->form->reset();
    }

    public function render()
    {
        return view('livewire.application.navigation.form.form-sub-link-page');
    }
}
