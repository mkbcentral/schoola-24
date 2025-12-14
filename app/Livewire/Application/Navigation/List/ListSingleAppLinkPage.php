<?php

namespace App\Livewire\Application\Navigation\List;

use App\Domain\Utils\AppMessage;
use App\Models\SingleAppLink;
use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListSingleAppLinkPage extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public $q = '';

    protected $listeners = [
        'singleAppLinkListRefred' => '$refresh',
    ];

    public function edit(?SingleAppLink $singleAppLink)
    {
        $this->dispatch('singleAppLinkData', $singleAppLink);
    }

    public function delete(?SingleAppLink $singleAppLink)
    {
        try {
            $singleAppLink->delete();
            $this->dispatch('updated', ['message' => AppMessage::DATA_DELETED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.application.navigation.list.list-single-app-link-page', [
            'singleAppLinks' => SingleAppLink::query()
                ->where('name', 'like', '%' . $this->q . '%')
                ->paginate(10),
        ]);
    }
}
