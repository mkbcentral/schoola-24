<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Connexion')]
class Login extends Component
{
    #[Validate]
    public string $email = '';

    #[Validate]
    public string $password = '';

    public bool $remember = false;

    public bool $isLoading = false;

    public string $statusMessage = '';

    public int $maxAttempts = 5;

    public ?int $remainingAttempts = null;

    public ?string $warningMessage = null;

    protected function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
    }

    //message
    protected $messages = [
        'email.required' => 'L\'adresse e-mail est obligatoire.',
        'email.email' => 'L\'adresse e-mail doit être une adresse e-mail valide.',
        'password.required' => 'Le mot de passe est obligatoire.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
    ];

    /**
     * Tentative de connexion de l'utilisateur
     */
    public function login()
    {
        $this->warningMessage = null;

        try {
            $this->isLoading = true;
            $this->statusMessage = 'Vérification des identifiants...';

            $this->validate();

            $this->ensureIsNotRateLimited();

            $this->statusMessage = 'Connexion en cours...';

            if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
                RateLimiter::hit($this->throttleKey());

                $this->isLoading = false;
                $this->statusMessage = '';

                // Calculer les tentatives restantes
                $attempts = RateLimiter::attempts($this->throttleKey());
                $this->remainingAttempts = $this->maxAttempts - $attempts;

                // Message d'avertissement selon les tentatives restantes
                if ($this->remainingAttempts === 0) {
                    $seconds = RateLimiter::availableIn($this->throttleKey());
                    $minutes = ceil($seconds / 60);

                    throw ValidationException::withMessages([
                        'email' => "Trop de tentatives de connexion. Veuillez réessayer dans {$minutes} minute(s).",
                    ]);
                } elseif ($this->remainingAttempts <= 2) {
                    $this->warningMessage = "Attention ! Il vous reste {$this->remainingAttempts} tentative(s) avant le blocage temporaire de votre compte.";
                }

                throw ValidationException::withMessages([
                    'email' => "Ces identifiants ne correspondent pas à nos enregistrements. ({$this->remainingAttempts} tentative(s) restante(s))",
                ]);
            }

            RateLimiter::clear($this->throttleKey());

            session()->regenerate();

            $this->statusMessage = 'Redirection vers votre espace...';

            $this->redirect(
                session('url.intended', '/'),
                navigate: true
            );
        } catch (ValidationException $e) {
            $this->isLoading = false;
            $this->statusMessage = '';

            throw $e;
        }
    }

    /**
     * S'assurer que l'utilisateur n'est pas limité par le rate limiter
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $this->maxAttempts)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());
        $minutes = ceil($seconds / 60);

        $this->isLoading = false;
        $this->statusMessage = '';

        throw ValidationException::withMessages([
            'email' => "Trop de tentatives échouées. Votre compte est temporairement bloqué pour {$minutes} minute(s). Veuillez réessayer plus tard.",
        ]);
    }

    /**
     * Obtenir la clé de limitation de débit pour la requête
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
