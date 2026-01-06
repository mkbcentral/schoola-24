<?php

namespace App\Livewire\Application\V2\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Services\AuthenticationService;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest-v2')]
#[Title('Connexion')]
class Login extends Component
{
    // Propriétés du formulaire
    public string $identifier = '';
    public string $password = '';
    public bool $remember = false;

    // États de l'interface
    public bool $isLoading = false;
    public bool $showPassword = false;

    // Informations sur les tentatives
    public ?int $remainingAttempts = null;
    public ?int $lockoutTime = null;
    public bool $isLocked = false;

    /**
     * Règles de validation
     */
    protected function rules(): array
    {
        return [
            'identifier' => ['required', 'string', 'min:3'],
            'password' => ['required', 'string', 'min:4'],
            'remember' => ['boolean'],
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    protected function messages(): array
    {
        return [
            'identifier.required' => 'L\'identifiant est requis.',
            'identifier.min' => 'L\'identifiant doit contenir au moins 3 caractères.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 4 caractères.',
        ];
    }

    /**
     * Validation en temps réel
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Basculer la visibilité du mot de passe
     */
    public function togglePasswordVisibility(): void
    {
        $this->showPassword = !$this->showPassword;
    }

    /**
     * Tentative de connexion
     */
    public function login(AuthenticationService $authService): void
    {
        // Empêcher les soumissions multiples
        if ($this->isLoading) {
            return;
        }

        $this->isLoading = true;

        try {
            // Validation des champs
            $this->validate();

            // Créer le DTO avec les informations de la requête
            $loginDTO = LoginDTO::fromArray([
                'identifier' => $this->identifier,
                'password' => $this->password,
                'remember' => $this->remember,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Tentative de connexion via le service
            $result = $authService->login($loginDTO);

            // Gérer le résultat
            if ($result['success']) {
                // Connexion réussie
                session()->flash('success', $result['message']);

                // Rediriger vers le tableau de bord financier
                $this->redirect('/');
                return;
            } else {
                // Connexion échouée
                $this->remainingAttempts = $result['remainingAttempts'];
                $this->lockoutTime = $result['lockoutTime'];
                $this->isLocked = $this->lockoutTime !== null && $this->lockoutTime > 0;

                // Afficher le message d'erreur
                $this->addError('login', $result['message']);

                // Réinitialiser le mot de passe pour des raisons de sécurité
                $this->password = '';
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Les erreurs de validation sont déjà gérées
            throw $e;
        } catch (\Exception $e) {
            // Erreur inattendue
            $this->addError('login', 'Une erreur est survenue lors de la connexion. Veuillez réessayer.');

            // Logger l'erreur
            \Illuminate\Support\Facades\Log::error('Erreur lors de la connexion', [
                'error' => $e->getMessage(),
                'identifier' => $this->identifier,
            ]);
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Rediriger vers la page de réinitialisation de mot de passe
     */
    public function goToForgotPassword(): void
    {
        $this->redirect(route('password.request'), navigate: true);
    }

    /**
     * Render du composant
     */
    public function render()
    {
        return view('livewire.application.v2.auth.login');
    }
}
