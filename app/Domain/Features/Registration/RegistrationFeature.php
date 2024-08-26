<?php

namespace App\Domain\Features\Registration;

use App\Domain\Contract\Registration\IRegistration;
use App\Models\Registration;

class RegistrationFeature implements IRegistration
{
    /**
     * @inheritDoc
     */
    public static function create(array $input): Registration
    {
        return Registration::create($input);
    }
    /**
     * @inheritDoc
     */
    public static function delete(Registration $registration): bool
    {
        return $registration->delete();
    }
    /**
     * @inheritDoc
     */
    public static function get(int $id): Registration
    {
        return Registration::find($id);
    }
    /**
     * @inheritDoc
     */
    public static function update(Registration $registration, array $input): bool
    {
        return $registration->update($input);
    }
    /**
     * @inheritDoc
     */
    public static function makeAbandoned(Registration $registration): bool
    {
        $registration->abandoned != $registration->abandoned;
        return $registration->update();
    }

    /**
     * @inheritDoc
     */
    public static function makeClassChanged(Registration $registration): bool
    {
        $registration->classChanged != $registration->classChanged;
        return $registration->update();
    }

    /**
     * @inheritDoc
     */
    public static function makeIsRegistered(Registration $registration): bool
    {
        $registration->isRegistered != $registration->isRegistered;
        return $registration->update();
    }
    public static function getCount(
        string|null $date,
        string|null $month,
        int|null $sectionId,
        int|null $optionId,
        int|null $classRoomId,
        int|null $responsibleId,
        bool|null $isOld
    ): int|float {
        $filters = [
            'section_id' => $sectionId,
            'date' => $date,
            'month' => $month,
            'option_id' => $optionId,
            'class_room_id' => $classRoomId,
            'responsible_student_id' => $responsibleId,
            'is_old' => $isOld,
        ];
        return Registration::query()
            ->filterNotSorted($filters)
            ->count();
    }
    /**
     * @inheritDoc
     */
    public static function getTotalAmount(
        string|null $date,
        string|null $month,
        int|null $sectionId,
        int|null $optionId,
        int|null $classRoomId,
        int|null $responsibleId,
        bool|null $isOld
    ): int|float {
        $total = 0;
        $filters = [
            'date' => $date,
            'month' => $month,
            'section_id' => $sectionId,
            'option_id' => $optionId,
            'class_room_id' => $classRoomId,
            'responsible_student_id' => $responsibleId,
            'is_old' => $isOld,
        ];
        $registrations =  Registration::query()
            ->filterNotSorted($filters)
            ->get();
        foreach ($registrations as $registration) {
            $total += $registration->registrationFee->amount;
        }
        return $total;
    }

    /**
     * @inheritDoc
     */
    public static function getList(
        string|null $date,
        string|null $month,
        int|null $sectionId,
        int|null $optionId,
        int|null $classRoomId,
        int|null $responsibleId,
        string|null $q,
        string|null $sortBy,
        bool|null $sortAsc,
        int|null $per_page
    ): mixed {
        $filters = [
            'section_id' => $sectionId,
            'date' => $date,
            'month' => $month,
            'option_id' => $optionId,
            'class_room_id' => $classRoomId,
            'responsible_student_id' => $responsibleId,
            'sort_by' => $sortBy,
            'sort_asc' => $sortAsc,
            'q' => $q,
        ];
        return Registration::query()
            ->filter($filters)
            ->paginate($per_page);
    }
    /**
     * @inheritDoc
     */
    public static function getListOoldOrNew(
        string|null $date,
        string|null $month,
        int|null $sectionId,
        int|null $optionId,
        int|null $classRoomId,
        int|null $responsibleId,
        bool|null $isOld,
        string|null $q,
        string|null $sortBy,
        bool|null $sortAsc,
        int|null $per_page
    ): mixed {
        $filters = [
            'section_id' => $sectionId,
            'date' => $date,
            'month' => $month,
            'option_id' => $optionId,
            'class_room_id' => $classRoomId,
            'responsible_student_id' => $responsibleId,
            'sort_by' => $sortBy,
            'is_old' => $isOld,
            'sort_asc' => $sortAsc,
            'q' => $q,
        ];
        return Registration::query()
            ->filterOldOrnew($filters)
            ->paginate($per_page);
    }
    /**
     * @inheritDoc
     */
    public static function getCountAll(
        string|null $date,
        string|null $month,
        int|null $sectionId,
        int|null $optionId,
        int|null $classRoomId,
        int|null $responsibleId
    ): float|int {
        $filters = [
            'section_id' => $sectionId,
            'date' => $date,
            'month' => $month,
            'option_id' => $optionId,
            'class_room_id' => $classRoomId,
            'responsible_student_id' => $responsibleId,
        ];
        return Registration::query()
            ->filterCounterAll($filters)
            ->count();
    }
}
