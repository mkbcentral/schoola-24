<?php

namespace App\Livewire\Application\V2\User\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\RoleForm;
use App\Models\Role;
use App\Services\RoleService;
use Livewire\Attributes\On;
use Livewire\Component;

class RoleFormOffcanvas extends Component
{
    public RoleForm $form;
    public ?Role $role = null;
    public bool $isOpen = false;

    public function mount()
    {
        $this->isOpen = false;
    }

    #[On('open-create-role')]
    public function openCreate()
    {
        $this->reset(['role']);
        $this->form->reset();
        $this->isOpen = true;
        $this->dispatch('show-role-offcanvas');
    }

    #[On('edit-role')]
    public function openEdit(int $id, RoleService $roleService)
    {
        $this->role = $roleService->findById($id);

        if ($this->role) {
            $this->form->setRole($this->role);
            $this->isOpen = true;
            $this->dispatch('show-role-offcanvas');
        }
    }

    public function save(RoleService $roleService)
    {
        try {
            if ($this->role) {
                // Update existing role
                $this->form->update();
                $message = AppMessage::DATA_UPDATED_SUCCESS;
            } else {
                // Create new role
                $this->form->store();
                $message = AppMessage::DATA_SAVED_SUCCESS;
            }

            $this->dispatch('success-message', message: $message);
            $this->dispatch('role-saved');
            $this->dispatch('hide-role-offcanvas');
            $this->closeOffcanvas();
        } catch (\Exception $ex) {
            $this->dispatch('error-message', message: $ex->getMessage());
        }
    }

    public function closeOffcanvas()
    {
        $this->isOpen = false;
        $this->reset(['role']);
        $this->form->reset();
        $this->dispatch('hide-role-offcanvas');
    }

    public function render()
    {
        return view('livewire.application.v2.user.form.role-form-offcanvas');
    }
}
