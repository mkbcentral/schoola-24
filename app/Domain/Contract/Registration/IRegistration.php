<?php

namespace App\Domain\Contract\Registration;

use App\Models\Registration;
use Illuminate\Support\Collection;

interface  IRegistration
{
    //Créer une nouvelle inscription
    public static function create(array $input): Registration;
    //Recuperer un inscription
    public static function get(int $id): Registration;
    //Mettre à jour une inscription
    public static function update(Registration $registration, array $input): bool;
    //Retirer une inscription
    public static function delete(Registration $registration): bool;
    //Valider une inscription
    public static function makeIsRegistered(Registration $registration): bool;
    //Marquer une inscription comme abandon
    public static function makeAbandoned(Registration $registration): bool;
    //Marquer une inscription comme changée de classe
    public static function makeClassChanged(Registration $registration): bool;
    //Recuperer la liste de inscription par date
    public static function getListByDate(
        string $date,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
        bool $isOld
    ): Collection;
    //Recuperer la liste des inscriptions par mois
    public static function getListByMonth(
        string $month,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
        bool $isOld
    ): Collection;
    //Recuperer le nombre total des inscription par date
    public static function getTotalCountByDate(
        string $date,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
        bool $isOld
    ): int;
    //Recuperer le nombre total des inscription par mois
    public static function getTotalCountByMonth(
        string $month,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
        bool $isOld
    ): int;
    //Recuperer le montant total des inscription par date
    public static function getTotalAmountByDate(
        string $date,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
        bool $isOld
    ): int;
    //Recuperer le montant total des inscription par mois
    public static function getTotalAmountByMonth(
        string $month,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
        bool $isOld
    ): int;
}
