<?php

namespace App\Livewire\Application\Admin;

use App\Domain\Utils\AppMessage;
use App\Models\SingleAppLink;
use App\Models\User;
use Exception;
use Livewire\Component;

class AttacheSingleMenuToUserPage extends Component
{
    public User $user;
    public $selectedLinks = [];

    public function mount()
    {
        $this->selectedLinks = $this->user->singleAppLinks()->pluck('single_app_link_id')->map(function ($id) {
            return (string) $id;
        });
    }

    public function attachLinks(): void
    {
        try {
            $this->user->singleAppLinks()->sync($this->selectedLinks);
            $this->dispatch('added', ['message', AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message', $ex->getMessage()]);
        }
    }
    public function render()
    {
        return view('livewire.application.admin.attache-single-menu-to-user-page', [
            'singleAppLinks' => SingleAppLink::all()
        ]);
    }
}
