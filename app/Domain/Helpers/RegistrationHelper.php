<?php

namespace App\Domain\Helpers;

use App\Models\ClassRoom;
use App\Models\School;;

class RegistrationHelper
{
    public static function gerenateRegistrationCode(int $class_room_id, string $code): string
    {
        $output = '';
        $classRoom = ClassRoom::findOrFail($class_room_id);
        $output = substr($classRoom->option->name, 0, 3)
            . '-'  . $code . '-' .
            substr(School::DEFAULT_SCHOOL_NAME(), 0, 2);
        return $output;
    }
}
