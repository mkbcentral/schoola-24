<?php

namespace App\Livewire\Application\Navigation\List;

use App\Domain\Utils\AppMessage;
use App\Models\SubLink;
use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListSublinkPage extends Component
{
    use WithPagination;
    #[Url(as: 'q')]
    public $q = '';
    protected $listeners = [
        'subLinkListRefred' => '$refresh'
    ];

    public function edit(?SubLink $subLink)
    {
        $this->dispatch('subLinkData', $subLink);
    }

    public function delete(?SubLink $subLink)
    {
        try {
            $subLink->delete();
            $this->dispatch('updated', ['message' => AppMessage::DATA_DELETED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.application.navigation.list.list-sublink-page', [
            'subLinks' => SubLink::query()
                ->where('name', 'like', '%' . $this->q . '%')
                ->paginate(10)
        ]);
    }
}
