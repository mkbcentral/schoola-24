<?php

namespace App\Livewire\Application\Config\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\ClassRoomFrom;
use App\Models\ClassRoom;
use Exception;
use Livewire\Component;

class FormClassRoomPage extends Component
{
    protected $listeners = ['classRoomData' => 'getClassRoom'];

    public ?ClassRoom $classRoomSelected = null;

    public ClassRoomFrom $form;

    public function getClassRoom(ClassRoom $classRoom)
    {
        $this->classRoomSelected = $classRoom;
        $this->form->fill($this->classRoomSelected->toArray());
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
            $this->form->update($this->classRoomSelected, $input);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function handlerSubmit()
    {
        if ($this->classRoomSelected == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->form->reset();
        $this->dispatch(
            'classRoomDataRefreshed'
        );
    }

    public function cancelUpdate()
    {
        $this->classRoomSelected = null;
        $this->form->reset();
    }

    public function render()
    {
        return view('livewire.application.config.form.form-class-room-page');
    }
}
