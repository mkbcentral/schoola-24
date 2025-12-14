<?php

namespace App\Livewire\Traits;

trait WithFlashMessages
{
    public string $message = '';
    public string $messageType = 'success';

    /**
     * Afficher un message de succès
     */
    protected function success(string $message): void
    {
        $this->message = $message;
        $this->messageType = 'success';
    }

    /**
     * Afficher un message d'erreur
     */
    protected function error(string $message): void
    {
        $this->message = $message;
        $this->messageType = 'error';
    }

    /**
     * Réinitialiser les messages
     */
    protected function clearMessage(): void
    {
        $this->message = '';
        $this->messageType = 'success';
    }
}
