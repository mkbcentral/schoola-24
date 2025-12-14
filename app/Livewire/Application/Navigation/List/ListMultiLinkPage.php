<?php

namespace App\Livewire\Application\Navigation\List;

use App\Domain\Utils\AppMessage;
use App\Models\MultiAppLink;
use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListMultiLinkPage extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public $q = '';

    protected $listeners = [
        'multiAppLinkListRefred' => '$refresh',
    ];

    public function edit(?MultiAppLink $multiAppLink)
    {
        $this->dispatch('multiAppLinkData', $multiAppLink);
    }

    public function delete(?MultiAppLink $multiAppLink)
    {
        try {
            $multiAppLink->delete();
            $this->dispatch('updated', ['message' => AppMessage::DATA_DELETED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.application.navigation.list.list-multi-link-page', [
            'multiAppLinks' => MultiAppLink::query()
                ->where('name', 'like', '%' . $this->q . '%')
                ->paginate(10),
        ]);
    }
}
