<?php

namespace App\Domain\Features\Registration;

use App\Domain\Contract\Registration\IRegistration;
use App\Domain\Features\Student\StudentFeature;
use App\Models\Payment;
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
        $status = false;
        $reg = RegistrationFeature::delete($registration);
        Payment::where('registration_id', $registration->id)->delete();
        StudentFeature::delete($registration->student);
        $status = true;
        return $status;
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
        return $registration->update(
            [
                'registration_fee_id' => $input['registration_fee_id'],
                'class_room_id' => $input['class_room_id'],
                'is_old' => $input['is_old'],
                'created_at' => $input['created_at'],
            ]
        );
    }
    /**
     * @inheritDoc
     */
    public static function makeAbandoned(Registration $registration): bool
    {
        $registration->abandoned = !$registration->abandoned;
        return $registration->update();
    }

    /**
     * @inheritDoc
     */
    public static function makeClassChanged(Registration $registration): bool
    {
        $registration->class_changed = !$registration->class_changed;
        return $registration->update();
    }

    /**
     * @inheritDoc
     */
    public static function makeIsRegistered(Registration $registration): bool
    {
        $registration->is_registered = !$registration->is_registered;
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
        $filters = self::getFilters($sectionId, $date, $month, $optionId, $classRoomId, $responsibleId, $isOld, null, null, null);
        return Registration::query()
            ->filter($filters)
            ->count();
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
        $filters = self::getFilters($sectionId, $date, $month, $optionId, $classRoomId, $responsibleId, null, null, null, '');
        return Registration::query()
            ->filter($filters)
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
        $filters = self::getFilters($sectionId, $date, $month, $optionId, $classRoomId, $responsibleId, $isOld, null, null, null);
        $registrations =  Registration::query()
            ->filter($filters)
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
        bool|null $isOld,
        int|null $per_page
    ): array|\Illuminate\Pagination\LengthAwarePaginator {
        $filters = self::getFilters(
            $sectionId,
            $date,
            $month,
            $optionId,
            $classRoomId,
            $responsibleId,
            $isOld,
            null,
            null,
            $q
        );
        return Registration::query()
            ->filter($filters)
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')
            ->paginate($per_page);
    }
    /**
     * @inheritDoc
     */
    public static function getListOldOrNew(
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
    ): array|\Illuminate\Pagination\LengthAwarePaginator {
        $filters = self::getFilters($sectionId, $date, $month, $optionId, $classRoomId, $responsibleId, $isOld, null, null, $q);
        return Registration::query()
            ->filter($filters)
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')
            ->paginate($per_page);
    }


    public static function getFilters(mixed $sectionId, mixed $date, mixed $month, mixed $optionId, mixed $classRoomId, mixed $responsibleId, mixed $isOld, mixed $sortBy, mixed $sortAsc, mixed $q): array
    {
        return [
            'section_id' => $sectionId,
            'date' => $date,
            'month' => $month,
            'option_id' => $optionId,
            'class_room_id' => $classRoomId,
            'responsible_student_id' => $responsibleId,
            'is_old' => $isOld,
            'sort_by' => $sortBy,
            'sort_asc' => $sortAsc,
            'q' => $q,
        ];
    }

    public static function generateQRCodes(array $items): void
    {
        $registrations = Registration::whereIn('id', $items)->get();
        foreach ($registrations as $registration) {
            $qrcode = StudentFeature::generateStudentQRCode($registration);
            $registration->update(['qr_code' => $qrcode]);
        }
    }
}
