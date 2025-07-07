<?php

namespace App\Livewire\Application\Auth;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\AuthForm;
use Illuminate\Support\Facades\Auth;
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
        } catch (\Exception $ex) {
            $this->dispatch('error', ['message' => AppMessage::EXECPTIONçERROR]);
        }
    }




    public function render()
    {
        return view('livewire.application.auth.login-page');
    }
}
