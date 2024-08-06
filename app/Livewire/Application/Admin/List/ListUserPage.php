<?php

namespace App\Livewire\Application\Admin\List;

use App\Domain\Features\Admin\UserFeature;
use App\Models\User;
use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListUserPage extends Component
{
    use WithPagination;
    protected $listeners = ['userDataRefreshed' => '$refresh'];
    public int $per_page = 10;

    #[Url(as: 'q')]
    public $q = '';
    #[Url(as: 'sortBy')]
    public $sortBy = 'name';
    #[Url(as: 'sortAsc')]
    public $sortAsc = true;

    public function sortData($value): void
    {
        if ($value == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $value;
    }

    public function addNewUser()
    {
        $this->dispatch('open-form-user');
    }

    public function edit(User $user): void
    {
        $this->dispatch('open-form-user');
        $this->dispatch('userData', $user);
    }

    public function activateUser(User $user): void
    {
        try {
            if ($user->is_active == true) {
                $user->is_active = false;
            } else {
                $user->is_active = true;
            }
            $user->update();
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.application.admin.list.list-user-page', [
            'users' => UserFeature::getListUser(
                $this->q,
                $this->sortBy,
                $this->sortAsc,
                $this->per_page
            ),
        ]);
    }
}
