<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Vérification de l\'email')]
class VerifyEmail extends Component
{
    public ?string $status = null;

    public function mount()
    {
        if (! Auth::user() instanceof MustVerifyEmail) {
            $this->redirect('/home', navigate: true);
        }

        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirect('/home', navigate: true);
        }
    }

    public function resendVerificationEmail()
    {
        Auth::user()->sendEmailVerificationNotification();

        $this->status = 'Un nouveau lien de vérification a été envoyé à votre adresse email.';
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
