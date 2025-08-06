<?php

namespace App\Livewire\Application\Auth;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\AuthForm;
use Exception;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Str;

class LoginPage extends Component
{
    public AuthForm $form;
    /**
     * @return void
     */
    public function login(): void
    {
        $this->validate();
        $this->ensureIsNotRateLimited();
        try {
            if ($this->form->login()) {
                if (!Auth::user()->is_active) {
                    Auth::logout();
                    $this->dispatch('error', ['message' => AppMessage::LOGGED_IN_FAILLED_TO_UNACTIVATE_USER]);
                } else {
                    $this->dispatch('added', ['message' => AppMessage::LOGGED_IN_SUCCESS]);
                    $this->redirect('/');;
                }
            } else {
                $this->dispatch('error', ['message' => AppMessage::LOGGED_IN_FAILLED]);
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => AppMessage::LOGGED_IN_FAILLED . ' ' . $ex->getMessage()]);
        }
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->form->login) . '|' . request()->ip());
    }





    public function render()
    {
        return view('livewire.application.auth.login-page');
    }
}
