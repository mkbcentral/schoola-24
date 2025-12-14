<?php

namespace App\Livewire\Application\Admin\List;

use App\Domain\Features\Admin\UserFeature;
use App\Domain\Utils\AppMessage;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
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
            $this->sortAsc = ! $this->sortAsc;
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
            $user->is_active = ! $user->is_active;
            $user->update();
            $this->dispatch('added', ['message' => AppMessage::ACTION_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function deleteUser(User $user): void {}

    public function resetUserPassword(User $user): void
    {
        try {
            $defaultPassword = 'password';
            $user->password = \Hash::make($defaultPassword);
            $user->update();
            $this->dispatch('added', ['message' => AppMessage::ACTION_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.admin.list.list-user-page', [
            'users' => Auth::user()->role->is_for_school ?
                UserFeature::getListSchoolUser(
                    $this->q,
                    $this->sortBy,
                    $this->sortAsc,
                    $this->per_page
                ) : UserFeature::getListAppUser(
                    $this->q,
                    $this->sortBy,
                    $this->sortAsc,
                    $this->per_page
                ),
        ]);
    }
}
