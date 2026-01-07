<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Authentification à deux facteurs')]
class TwoFactorChallenge extends Component
{
    #[Rule('nullable|string')]
    public string $code = '';

    #[Rule('nullable|string')]
    public string $recovery_code = '';

    public bool $useRecoveryCode = false;

    public function authenticate()
    {
        if ($this->useRecoveryCode) {
            $this->validateRecoveryCode();
        } else {
            $this->validateCode();
        }

        session()->regenerate();

        $this->redirect(
            session('url.intended', config('fortify.home', '/home')),
            navigate: true
        );
    }

    protected function validateCode()
    {
        $this->validate([
            'code' => 'required|string',
        ]);

        $user = Auth::user();

        $codeIsValid = app(TwoFactorAuthenticationProvider::class)
            ->verify(decrypt($user->two_factor_secret), $this->code);

        if (! $codeIsValid) {
            throw ValidationException::withMessages([
                'code' => __('Le code fourni est invalide.'),
            ]);
        }
    }

    protected function validateRecoveryCode()
    {
        $this->validate([
            'recovery_code' => 'required|string',
        ]);

        $user = Auth::user();

        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

        if (! in_array($this->recovery_code, $recoveryCodes)) {
            throw ValidationException::withMessages([
                'recovery_code' => __('Le code de récupération fourni est invalide.'),
            ]);
        }

        // Remove used recovery code
        $user->replaceRecoveryCode($this->recovery_code);
    }

    public function toggleRecoveryCode()
    {
        $this->useRecoveryCode = ! $this->useRecoveryCode;
        $this->code = '';
        $this->recovery_code = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge');
    }
}
