<?php

namespace App\Livewire\Application\Navigation\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\MultiLinkForm;
use App\Models\MultiAppLink;
use Exception;
use Livewire\Component;

class FormMultiLinkPage extends Component
{
    protected $listeners = [
        'multiAppLinkData' => 'getMultiAppLink'
    ];
    public ?MultiAppLink $multiAppLink = null;
    public MultiLinkForm $form;
    public function getMultiAppLink(MultiAppLink $multiAppLink): void
    {
        $this->multiAppLink = $multiAppLink;
        $this->form->fill($multiAppLink->toArray());
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
            $this->form->update($this->multiAppLink);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function handlerSubmit(): void
    {
        $this->validate();
        if ($this->multiAppLink == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->form->reset();
        $this->multiAppLink = null;
        $this->dispatch('multiAppLinkListRefred');
    }
    public function cancelUpdate(): void
    {
        $this->multiAppLink = null;
        $this->form->reset();
    }
    public function render()
    {
        return view('livewire.application.navigation.form.form-multi-link-page');
    }
}
