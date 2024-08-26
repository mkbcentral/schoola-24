<?php

namespace App\Livewire\Application\Admin\Form;

use App\Domain\Utils\AppMessage;
use App\Enums\RoleType;
use App\Livewire\Forms\UserForm;
use App\Models\Role;
use App\Models\School;
use Exception;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class FormUserAdminPage extends Component
{
    protected $listeners = [
        "schoolConfigData" => "getSchool"
    ];
    public ?School $school = null;
    public UserForm $form;
    public function save(): void
    {
        $input = $this->validate();
        try {
            $input['school_id'] = $this->school->id;
            $input['password'] = Hash::make('password');
            $this->form->create($input);
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
            $this->dispatch('schoolConfigDataRefreshed');
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function getSchool(?School $school = null)
    {
        $this->school = $school;
    }

    public function mount(): void
    {
        $this->form->is_active = true;
        $this->form->role_id = Role::where('name', RoleType::ADMIN_SCHOOL)->first()->id;
    }

    public function render()
    {
        return view('livewire.application.admin.form.form-user-admin-page');
    }
}
