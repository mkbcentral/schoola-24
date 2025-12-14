<?php

namespace App\Livewire\Application\Admin\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\UserForm;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class FormUserPage extends Component
{
    protected $listeners = ['userData' => 'getUser'];

    public ?User $user = null;

    public UserForm $form;

    public function save(): void
    {
        $input = $this->validate();
        try {
            $role = Role::find($this->form->role_id);
            if ($role->is_for_school == true) {
                $input['school_id'] = School::DEFAULT_SCHOOL_ID();
            }
            $input['password'] = Hash::make('password');
            $this->form->create($input);
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function getUser(?User $user = null)
    {
        $this->user = $user;
        $this->form->fill($user->toArray());
    }

    public function update(): void
    {
        $input = $this->validate();
        try {
            $this->form->update($this->user, $input);
            $this->dispatch('updated', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => AppMessage::EXECPTIONçERROR]);
        }
    }

    public function handlerSubmit(): void
    {
        if ($this->user == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->dispatch('userDataRefreshed');
        $this->dispatch('close-form-user');
    }

    public function render()
    {
        return view('livewire.application.admin.form.form-user-page');
    }
}
