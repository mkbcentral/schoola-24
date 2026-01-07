<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Confirmer le mot de passe')]
class ConfirmPassword extends Component
{
    #[Rule('required|string')]
    public string $password = '';

    public function confirmPassword()
    {
        $this->validate();

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('Le mot de passe est incorrect.'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirect(session('url.intended', config('fortify.home', '/home')), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.confirm-password');
    }
}
