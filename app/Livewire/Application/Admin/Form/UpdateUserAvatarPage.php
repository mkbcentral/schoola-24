<?php

namespace App\Livewire\Application\Admin\Form;

use App\Domain\Utils\AppMessage;
use Exception;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class UpdateUserAvatarPage extends Component
{
    use WithFileUploads;

    public $image;

    public function updatedImage()
    {
        try {
            $path = $this->image->store('school/users/avatars', 'public');
            auth()->user()->update(['avatar' => $path]);
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.application.admin.form.update-user-avatar-page');
    }
}
