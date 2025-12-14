<?php

namespace App\Livewire\Application\Config\List;

use App\Domain\Features\Configuration\SchoolDataFeature;
use App\Domain\Utils\AppMessage;
use App\Models\Section;
use Exception;
use Livewire\Component;

class ListSectionPage extends Component
{
    protected $listeners = ['sectionDataRefreshed' => '$refresh'];

    public function edit(?Section $section)
    {
        $this->dispatch('sectionData', $section);
    }

    public function delete(?Section $section)
    {
        try {
            if ($section->options->isEmpty()) {
                $section->delete();
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
        return view('livewire.application.config.list.list-section-page', [
            'sections' => SchoolDataFeature::getSectionList(),
        ]);
    }
}
