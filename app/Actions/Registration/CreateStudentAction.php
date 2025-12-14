<?php

namespace App\Actions\Registration;

use App\DTOs\Registration\CreateStudentDTO;
use App\Models\Student;

class CreateStudentAction
{
    /**
     * Create a new student
     */
    public function execute(CreateStudentDTO $dto): Student
    {
        return Student::create($dto->toArray());
    }
}
