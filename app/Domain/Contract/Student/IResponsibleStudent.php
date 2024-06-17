<?php

namespace App\Domain\Contract\Student;

use App\Models\ResponsibleStudent;
use Illuminate\Support\Collection;

interface  IResponsibleStudent
{
    public static function create(array $input): ResponsibleStudent; //Ajouter un nouveau resp
    public static function get(int $id): ResponsibleStudent; //Recuperer un resp
    public static function getList(): Collection; //Recuperer la liste des resp
    public static function update(ResponsibleStudent $responsibleStudent, array $input): bool; //Mettre à jour un resp
    public static function delete(ResponsibleStudent $responsibleStudent): bool; //Retirer un resp
}
