<?php

namespace App\Livewire\Application\Config\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\OptionFrom;
use App\Models\Option;
use Exception;
use Livewire\Component;

class FormOptionPage extends Component
{
    protected $listeners = ["optionData" => "getOption"];
    public ?Option $optionSelected = null;
    public OptionFrom $form;


    public function getOption(Option $option)
    {
        $this->optionSelected = $option;
        $this->form->fill($this->optionSelected->toArray());
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
            $this->form->update($this->optionSelected, $input);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function handlerSubmit()
    {
        if ($this->optionSelected == null) {
            $this->save();
        } else {
            $this->update();
            $this->optionSelected = null;
        }
        $this->form->reset();
        $this->dispatch('optionDataRefreshed');
    }

    public function cancelUpdate()
    {
        $this->optionSelected = null;
        $this->form->reset();
    }


    public function render()
    {
        return view('livewire.application.config.form.form-option-page');
    }
}
