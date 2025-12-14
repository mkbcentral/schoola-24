<?php

namespace App\Livewire\Application\Admin\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\RoleForm;
use App\Models\Role;
use Exception;
use Livewire\Component;

class FormRolePage extends Component
{
    protected $listeners = ['roleData' => 'getRole'];

    public ?Role $roleSelected = null;

    public RoleForm $form;

    public function getRole(Role $role)
    {
        $this->roleSelected = $role;
        $this->form->fill($role->toArray());
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
            $this->form->update($this->roleSelected, $input);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function handlerSubmit()
    {
        if ($this->roleSelected == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->dispatch('roleDataRefreshed');
        $this->form->reset();
    }

    public function cancelUpdate()
    {
        $this->roleSelected = null;
        $this->form->reset();
    }

    public function render()
    {
        return view('livewire.application.admin.form.form-role-page');
    }
}
