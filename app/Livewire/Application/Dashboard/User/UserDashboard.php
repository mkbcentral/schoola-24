<?php

namespace App\Livewire\Application\Dashboard\User;

use App\Models\User;
use Livewire\Component;

class UserDashboard extends Component
{
    public function render()
    {
        return view('livewire.application.dashboard.user.user-dashboard', [
            'onlines' => User::where('is_on_line', true)->count(),
            'offlines' => User::where('is_on_line', false)->count(),
            'desactivateds' => User::where('is_active', false)->count(),
            'listOnlines' => User::where('is_on_line', true)->get(),
        ]);
    }
}
