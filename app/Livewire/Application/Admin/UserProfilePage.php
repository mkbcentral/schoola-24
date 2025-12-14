<?php

namespace App\Livewire\Application\Admin;

use Livewire\Component;

class UserProfilePage extends Component
{
    protected $listeners = ['userProfileRefreshed' => '$refresh'];

    public function render()
    {
        return view('livewire.application.admin.user-profile-page');
    }
}
