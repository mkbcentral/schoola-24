<?php

namespace App\Livewire\Application\Auth;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\AuthForm;
use Livewire\Component;

class LoginPage extends Component
{
    public AuthForm $form;
    /**
     * @return void
     */
    public function login(): void
    {
        $this->validate();
        try {
            if ($this->form->login()) {
                $this->dispatch('added', ['message' => AppMessage::LOGGED_IN_SUCCESS]);
                $this->redirect('/');;
            } else {
                $this->dispatch('error', ['message' => AppMessage::LOGGED_IN_FAILLED]);
            }
        } catch (\Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function render()
    {
        return view('livewire.application.auth.login-page');
    }
}
