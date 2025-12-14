<?php

namespace App\Livewire\Application\Admin;

use App\Domain\Utils\AppMessage;
use App\Models\MultiAppLink;
use App\Models\User;
use Exception;
use Livewire\Component;

class AttachMultiAppLinkToUserPage extends Component
{
    public User $user;

    public $selectedLinks = [];

    public function mount()
    {
        $this->selectedLinks = $this->user->multiAppLinks()->pluck('multi_app_link_id')->map(function ($id) {
            return (string) $id;
        });
    }

    public function attachLinks(): void
    {
        try {
            $this->user->multiAppLinks()->sync($this->selectedLinks);
            $this->dispatch('added', ['message', AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message', $ex->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.application.admin.attach-multi-app-link-to-user-page', [
            'multiAppLinks' => MultiAppLink::all(),
        ]);
    }
}
