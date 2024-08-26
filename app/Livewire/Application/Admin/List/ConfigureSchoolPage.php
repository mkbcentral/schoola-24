<?php

namespace App\Livewire\Application\Admin\List;

use App\Enums\RoleType;
use App\Models\School;
use App\Models\User;
use Livewire\Component;

class ConfigureSchoolPage extends Component
{
    protected $listeners = ["schoolConfigDataRefreshed" => '$refresh'];

    public School $school;
    public ?User $user;
    public function mount(): void
    {
        $this->user = User::query()
            ->join('roles', 'roles.id', 'users.role_id')
            ->where('users.school_id', $this->school->id)
            ->where('roles.name', RoleType::ADMIN_SCHOOL)
            ->select('users.*')
            ->first();
    }
    public function edit(): void
    {
        $this->dispatch('schoolData', $this->school);
    }

    public function newUserAdmin(): void
    {
        $this->dispatch('schoolConfigData', $this->school);
    }

    public function render()
    {
        return view('livewire.application.admin.list.configure-school-page');
    }
}
