<?php

namespace App\Domain\Contract\Student;

use App\Models\Student;
use Illuminate\Support\Collection;

interface  IStudent
{
    public static function create(array $input): Student; //Ajouter un nouvel élève
    public static function get(int $id): Student; //returner un éleves
    public static function getList(): Collection; //Recuperer la liste des éléves
    public static function update(Student $student, array $input): bool; //Mettre à jour un éléve
    public static function delete(Student $student): bool; //Retirer un éléves
}
