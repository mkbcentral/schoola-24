<?php

namespace App\Domain\Helpers;

use App\Models\ClassRoom;
use App\Models\Registration;
use App\Models\School;
use App\Models\SchoolYear;

class RegistrationHelper
{
    public static function gerenateRegistrationCode(int $class_room_id, string $code): string
    {
        $output = '';
        $classRoom = ClassRoom::findOrFail($class_room_id);
        $counter = Registration::query()
            ->join('students', 'students.id', 'registrations.student_id')
            ->join('responsible_students', 'responsible_students.id', 'students.responsible_student_id')
            ->where('registrations.class_room_id', $class_room_id)
            ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->where('responsible_students.school_id', School::DEFAULT_SCHOOL_ID())
            ->count();
        $output = substr($classRoom->option->name, 0, 3)
            . '-' . $counter + 1 . '' . '-' . $code . '-' .
            substr(School::DEFAULT_SCHOOL_NAME(), 0, 2);
        return $output;
    }
}
