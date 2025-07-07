<?php

namespace App\Livewire\Application\Admin\Form;

use App\Domain\Utils\AppMessage;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditUserInfoPage extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function update(): void
    {
        $inputs = $this->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
        ]);
        try {
            Auth::user()->update($inputs);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
            $this->dispatch('userProfileRefreshed');
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function render()
    {
        return view('livewire.application.admin.form.edit-user-info-page');
    }
}
