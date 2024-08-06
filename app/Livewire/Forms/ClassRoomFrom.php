<?php

namespace App\Livewire\Forms;

use App\Models\ClassRoom;
use Livewire\Attributes\Rule;
use Livewire\Form;

class ClassRoomFrom extends Form
{

    #[Rule('required', message: "Nom classe obligation", onUpdate: false)]
    public $name = '';
    #[Rule('required', message: "Type classe obligation", onUpdate: false)]
    public $option_id = '';

    public function create(array $input): void
    {
        ClassRoom::create($input);
    }
    public function update(ClassRoom $classRoom, array $input): void
    {
        $classRoom->update($input);
    }
}
