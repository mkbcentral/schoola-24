<?php

namespace App\Livewire\Application\Config\List;

use App\Models\School;
use App\Models\SchoolYear;
use Livewire\Component;

class SchoolYearList extends Component
{

    public ?SchoolYear $selectedSchoolYear = null;
    public string $name = '';
    public bool $is_active = false;

    //edit
    public function edit(SchoolYear $schoolYear)
    {
        $this->selectedSchoolYear = $schoolYear;
        $this->name = $this->selectedSchoolYear->name;
        $this->is_active = $this->selectedSchoolYear->is_active;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        SchoolYear::create([
            'name' => $this->name,
            'is_active' => $this->is_active,
            'school_id' => School::DEFAULT_SCHOOL_ID(),
        ]);

        $this->dispatch('added', ['message' => __('Année scolaire créée avec succès')]);
    }

    //update
    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($this->selectedSchoolYear) {
            $this->selectedSchoolYear->update([
                'name' => $this->name,
                'is_active' => $this->is_active,
            ]);

            $this->dispatch('updated', ['message' => __('Année scolaire mise à jour avec succès')]);
        }
    }

    //reset form
    public function resetForm()
    {
        $this->selectedSchoolYear = null;
        $this->name = '';
        $this->is_active = false;
    }

    //handler submit
    public function handlerSubmit()
    {
        if ($this->selectedSchoolYear == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->resetForm();
        $this->dispatch('schoolYearDataRefreshed');
    }

    //delete if registrations is empty
    public function delete(SchoolYear $schoolYear)
    {
        if ($schoolYear->registrations()->count() > 0) {
            $this->dispatch('error', ['message' => __('Impossible de supprimer cette année scolaire, des inscriptions existent.')]);
            return;
        }
        $schoolYear->delete();
        $this->dispatch('deleted', ['message' => __('Année scolaire supprimée avec succès')]);
        $this->resetForm();
    }


    public function render()
    {
        return view('livewire.application.config.list.school-year-list', [
            'schoolYears' => SchoolYear::query()
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->get(),
        ]);
    }
}
