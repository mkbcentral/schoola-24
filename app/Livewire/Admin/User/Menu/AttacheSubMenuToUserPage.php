<?php

namespace App\Livewire\Admin\User\Menu;

use App\Domain\Utils\AppMessage;
use App\Models\SubLink;
use App\Models\User;
use Exception;
use Livewire\Component;

class AttacheSubMenuToUserPage extends Component
{
    public User $user;

    public $selectedLinks = [];

    public function mount()
    {
        $this->selectedLinks = $this->user->subLinks()->pluck('sub_link_id')->map(function ($id) {
            return (string) $id;
        });
    }

    public function attachLinks(): void
    {
        try {
            $this->user->subLinks()->sync($this->selectedLinks);
            $this->dispatch('added', ['message', AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message', $ex->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.application.admin.attache-sub-menu-to-user-page', [
            'subLinks' => SubLink::all(),
        ]);
    }
}
