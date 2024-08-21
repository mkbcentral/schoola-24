<?php

namespace App\Livewire\Application\Navigation\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\SingleAppLinkForm;
use App\Models\SingleAppLink;
use Exception;
use Livewire\Component;

class FormSingleAppLinkPage extends Component
{
    protected $listeners = [
        'singleAppLinkData' => 'getSingleAppLink'
    ];
    public ?SingleAppLink $singleAppLink = null;
    public SingleAppLinkForm $form;

    public function getSingleAppLink(SingleAppLink $singleAppLink): void
    {
        $this->singleAppLink = $singleAppLink;
        $this->form->fill($singleAppLink->toArray());
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
            $this->form->update($this->singleAppLink);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function handlerSubmit(): void
    {
        $this->validate();
        if ($this->singleAppLink == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->form->reset();
        $this->singleAppLink = null;
        $this->dispatch('singleAppLinkListRefred');
    }
    public function cancelUpdate(): void
    {
        $this->singleAppLink = null;
        $this->form->reset();
    }
    public function render()
    {
        return view('livewire.application.navigation.form.form-single-app-link-page');
    }
}
