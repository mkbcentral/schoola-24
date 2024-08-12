<?php

namespace App\Domain\Contract\Registration;

use App\Models\Registration;

interface  IRegistration
{

    /**
     * Créer une nouvelle inscription
     * @param array $input
     * @return \App\Models\Registration
     */
    public static function create(array $input): Registration;

    /**
     * Recuperer une inscription
     * @param int $id
     * @return \App\Models\Registration
     */
    public static function get(int $id): Registration;

    /**
     * Mettre à jour une inscription
     * @param \App\Models\Registration $registration
     * @param array $input
     * @return bool
     */
    public static function update(Registration $registration, array $input): bool;

    /**
     * Retirer une inscription
     * @param \App\Models\Registration $registration
     * @return bool
     */
    public static function delete(Registration $registration): bool;

    /**
     * Valider une inscription
     * @param \App\Models\Registration $registration
     * @return bool
     */
    public static function makeIsRegistered(Registration $registration): bool;

    /**
     * Marquer une inscription comme abandon
     * @param \App\Models\Registration $registration
     * @return bool
     */
    public static function makeAbandoned(Registration $registration): bool;
    /**
     * Marquer une inscription comme changée de classe
     * @param \App\Models\Registration $registration
     * @return bool
     */
    public static function makeClassChanged(Registration $registration): bool;
    /**
     * Summary of getList
     * @param mixed $date
     * @param mixed $month
     * @param mixed $sectionId
     * @param mixed $optionId
     * @param mixed $classRoomId
     * @param mixed $responsibleId
     * @param mixed $isOld
     * @param mixed $q
     * @param mixed $sortBy
     * @param mixed $sortAsc
     * @param mixed $per_page
     * @return mixed
     */
    public static function getList(
        ?string $date,
        ?string $month,
        ?int $sectionId,
        ?int $optionId,
        ?int $classRoomId,
        ?int $responsibleId,
        ?bool $isOld,
        ?string $q,
        ?string $sortBy,
        ?bool   $sortAsc,
        ?int $per_page
    ): mixed;

    /**
     * Summary of getList
     * @param mixed $date
     * @param mixed $month
     * @param mixed $sectionId
     * @param mixed $optionId
     * @param mixed $classRoomId
     * @param mixed $responsibleId
     * @param mixed $isOld
     * @param mixed $q
     * @param mixed $sortBy
     * @param mixed $sortAsc
     * @param mixed $per_page
     * @return mixed
     */
    public static function getCount(
        ?string $date,
        ?string $month,
        ?int $sectionId,
        ?int $optionId,
        ?int $classRoomId,
        ?int $responsibleId,
        ?bool $isOld,
    ): mixed;

    public static function getTotalAmount(
        ?string $date,
        ?string $month,
        ?int $sectionId,
        ?int $optionId,
        ?int $classRoomId,
        ?int $responsibleId,
        ?bool $isOld,
    ): mixed;
}
