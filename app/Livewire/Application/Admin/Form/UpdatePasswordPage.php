<?php

namespace App\Livewire\Application\Admin\Form;

use App\Domain\Utils\AppMessage;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UpdatePasswordPage extends Component
{
    public $old_password = '';
    public $current_password = '';
    public $confirm_password = '';

    public function update(): void
    {
        $this->validate([
            'current_password' => ['required'],
            'old_password' => ['required'],
            'confirm_password' => ['required'],
        ]);
        $user = Auth::user();

        try {
            $user = Auth::user();
            if (Hash::check($this->old_password, $user->password)) {
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
