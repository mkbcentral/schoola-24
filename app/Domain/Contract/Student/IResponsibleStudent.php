<?php

namespace App\Domain\Contract\Student;

use App\Models\ResponsibleStudent;

interface  IResponsibleStudent
{
    /**
     * create
     * Ajouter un nouveau resp
     * @param  mixed $input
     * @return ResponsibleStudent
     */
    public static function create(array $input): ResponsibleStudent;
    /**
     * get
     * Recuperer un resp
     * @param  mixed $id
     * @return ResponsibleStudent
     */
    public static function get(int $id): ResponsibleStudent;

    /**
     * getList
     * Recuperer la liste des resp
     * @return mixed
     */
    public static function getList(
        string $q,
        string $sortBy,
        bool   $sortAsc,
        int    $per_page = 20
    ): mixed;

    /**
     * update
     * Mettre à jour un resp
     * @param  mixed $responsibleStudent
     * @param  mixed $input
     * @return bool
     */
    public static function update(ResponsibleStudent $responsibleStudent, array $input): bool;

    /**
     * delete
     * Retirer un resp
     * @param  mixed $responsibleStudent
     * @return bool
     */
    public static function delete(ResponsibleStudent $responsibleStudent): bool;
}
