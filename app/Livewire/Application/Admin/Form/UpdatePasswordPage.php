<?php

namespace App\Livewire\Application\Admin\Form;

use App\Domain\Utils\AppMessage;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Livewire\Component;

class UpdatePasswordPage extends Component
{
    #[Rule('required', message: 'Ancien mot de passe est obligatoire')]
    public $old_password = '';

    #[Rule('required', message: 'Mot de passe actuel est obligatoire')]
    #[Rule('min:8', message: 'Mot de passe est trop court')]
    public $current_password = '';

    #[Rule('required', message: 'Mot de passe de confirmation est obligatoire')]
    public $confirm_password = '';

    public function updatePassword(): void
    {
        $this->validate();
        try {

            $user = Auth::user();
            if (! Hash::check($this->old_password, $user->password)) {
                $this->dispatch('error', ['message' => 'Ancien mot de passe incorrect']);
            } elseif ($this->current_password != $this->confirm_password) {
                $this->dispatch('error', ['message' => 'Confirmer votre mot de passe SVP!']);
            } else {
                $user->password = Hash::make($this->current_password);
                $user->update();
                $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.application.admin.form.update-password-page');
    }
}
