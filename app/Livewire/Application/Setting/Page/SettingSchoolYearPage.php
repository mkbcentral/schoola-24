<?php

namespace App\Livewire\Application\Setting\Page;

use App\Domain\Utils\AppMessage;
use App\Models\School;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SettingSchoolYearPage extends Component
{
    public $schoolYearId;
    public function mount()
    {
        $this->schoolYearId = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
    }

    public function updatedSchoolYearId($val)
    {
        // Deactivate all school years
        SchoolYear::query()->update(['is_active' => false]);

        // Activate the selected school year
        $schoolYear = SchoolYear::query()->where('id', $val)->first();
        if ($schoolYear) {
            $schoolYear->is_active = true;
            $schoolYear->user_id = Auth::id();
            $schoolYear->save();
            $user = Auth::user();
            $user->update([
                'work_on_year' => $schoolYear->id
            ]);
        }
        $this->dispatch('refreshSchoolYearLabel');
        $this->dispatch('updated', ['message', AppMessage::DATA_UPDATED_SUCCESS]);
    }
    public function render()
    {
        return view('livewire.application.setting.page.setting-school-year-page', [
            'schoolYears' => SchoolYear::latest()
                ->get()
        ]);
    }
}
