<?php

namespace App\Livewire\Application\V2\User\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\UserForm;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Component;

class UserFormOffcanvas extends Component
{
    public UserForm $form;
    public ?User $user = null;
    public bool $isOpen = false;

    public function mount()
    {
        $this->isOpen = false;
    }

    #[On('open-create-user')]
    public function openCreate()
    {
        $this->reset(['user']);
        $this->form->reset();
        $this->isOpen = true;
        $this->dispatch('show-user-offcanvas');
    }

    #[On('edit-user')]
    public function openEdit(int $id, UserService $userService)
    {
        $this->user = $userService->findById($id);

        if ($this->user) {
            $this->form->setUser($this->user);
            $this->isOpen = true;
            $this->dispatch('show-user-offcanvas');
        }
    }

    public function save(UserService $userService)
    {
        $this->validate();
        try {
            if ($this->user) {
                // Update existing user
                $this->form->update();
                $message = AppMessage::DATA_UPDATED_SUCCESS;
            } else {
                // Create new user
                $this->form->store();
                $message = AppMessage::DATA_SAVED_SUCCESS;
            }
       $this->dispatch('user-saved');
            $this->dispatch('hide-user-offcanvas');
            $this->closeOffcanvas();
             $this->dispatch('success-message', message: $message);
        } catch (\Exception $ex) {
            $this->dispatch('error-message', message: $ex->getMessage());
        }
    }

    public function closeOffcanvas()
    {
        $this->isOpen = false;
        $this->reset(['user']);
        $this->form->reset();
        $this->dispatch('hide-user-offcanvas');
    }

    public function render()
    {
        $roles = Role::orderBy('name')->get();

        return view('livewire.application.v2.user.form.user-form-offcanvas', [
            'roles' => $roles,
        ]);
    }
}
