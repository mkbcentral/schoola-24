<?php

namespace App\Livewire\Application\Admin\List;

use App\Domain\Utils\AppMessage;
use App\Models\Role;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class ListRolePage extends Component
{
    use WithPagination;

    public int $per_page = 5;

    protected $listeners = ['roleDataRefreshed' => '$refresh'];

    public function edit(?Role $role)
    {
        $this->dispatch('roleData', $role);
    }

    public function delete(?Role $role)
    {
        try {
            if ($role->users->isEmpty()) {
                $role->delete();
                $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
            } else {
                $this->dispatch('error', ['message' => AppMessage::ACTION_FAILLED]);
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.application.admin.list.list-role-page', [
            'roles' => Role::paginate($this->per_page),
        ]);
    }
}
