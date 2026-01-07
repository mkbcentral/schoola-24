<?php

namespace App\Livewire\Traits;

trait WithFlashMessages
{
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    /**
     * Afficher un message de succès
     */
    protected function success(string $message): void
    {
        $this->successMessage = $message;
        $this->errorMessage = null;
    }

    /**
     * Afficher un message d'erreur
     */
    protected function error(string $message): void
    {
        $this->errorMessage = $message;
        $this->successMessage = null;
    }

    /**
     * Effacer les messages
     */
    protected function clearMessage(): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;
    }
}
