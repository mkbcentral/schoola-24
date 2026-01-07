<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Mot de passe oublié')]
class ForgotPassword extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    public ?string $status = null;

    public function sendResetLink()
    {
        $this->validate();

        $status = Password::sendResetLink(
            ['email' => $this->email]
        );

        if ($status != Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        $this->status = __($status);
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
