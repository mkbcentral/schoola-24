<?php

namespace App\Domain\Contract\Registration;

use App\Models\Registration;
use App\Models\ResponsibleStudent;

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
     * Recuperer la liste de inscription par date
     * @param string $date
     * @param mixed $sectionId
     * @param mixed $optionId
     * @param int $classRoomId
     * @param bool $isOld
     * @param string $q
     * @param string $sortBy
     * @param bool $sortAsc
     * @param int $per_page
     * @return mixed
     */
    public static function getListByDate(
        string $date,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
        bool $isOld,
        string $q,
        string $sortBy,
        bool   $sortAsc,
        int $per_page = 20
    ): mixed;

    /**
     * Recuperer la liste des inscriptions par mois
     * @param string $month
     * @param mixed $sectionId
     * @param mixed $optionId
     * @param int $classRoomId
     * @param bool $isOld
     * @param string $q
     * @param string $sortBy
     * @param bool $sortAsc
     * @param int $per_page
     * @return mixed
     */
    public static function getListByMonth(
        string $month,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
        bool $isOld,
        string $q,
        string $sortBy,
        bool   $sortAsc,
        int $per_page = 20
    ): mixed;

    /**
     * Recuperer le nombre total des inscription par date
     * @param string $date
     * @param mixed $sectionId
     * @param mixed $optionId
     * @param int $classRoomId
     * @param bool $isOld
     * @return int
     */
    public static function getTotalCountByDate(
        string $date,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
        bool $isOld
    ): int;

    /**
     * Recuperer le nombre total des inscription par mois
     * @param string $month
     * @param mixed $sectionId
     * @param mixed $optionId
     * @param int $classRoomId
     * @param bool $isOld
     * @return int
     */
    public static function getTotalCountByMonth(
        string $month,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
        bool $isOld
    ): int;

    /**
     * Recuperer le montant total des inscription par date
     * @param string $date
     * @param mixed $sectionId
     * @param mixed $optionId
     * @param int $classRoomId
     * @param bool $isOld
     * @return int
     */
    public static function getTotalAmountByDate(
        string $date,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
        bool $isOld
    ): int;

    /**
     * Recuperer le montant total des inscription par mois
     * @param string $month
     * @param mixed $sectionId
     * @param mixed $optionId
     * @param int $classRoomId
     * @param bool $isOld
     * @return int
     */
    public static function getTotalAmountByMonth(
        string $month,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
        bool $isOld
    ): int;

    /**
     * Recuperer les inscription par classe
     * @param int $class_room_id
     * @param string $sortBy
     * @param bool $sortAsc
     * @return mixed
     */
    public static function getListByClassRoom(
        int $class_room_id,
        string $sortBy,
        bool   $sortAsc,
    ): mixed;
    /**
     * Recuperer le nombre d'inscription par classe
     * @param int $class_room_id
     * @param mixed $month
     * @return float|int
     */
    public static function getCountByClassRoom(int $class_room_id, $month = ""): float|int;

    /**
     * Recuprer la liste des inscription par responsable
     * @param mixed $responsibleStudent
     * @return mixed
     */
    public static function getListByResponsible(
        ?ResponsibleStudent $responsibleStudent
    ): mixed;

    /**
     * Recupeter
     * @param string $q
     * @param mixed $optionId
     * @param string $sortBy
     * @param bool $sortAsc
     * @param int $per_page
     * @return mixed
     */
    public static function getListAllInscription(
        string $q,
        ?int $optionId,
        string $sortBy,
        bool   $sortAsc,
        int $per_page = 20
    ): mixed;
}
