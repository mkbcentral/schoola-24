<?php

namespace App\Domain\Features\Registration;

use App\Domain\Contract\Registration\IRegistration;
use App\Models\Registration;
use App\Models\ResponsibleStudent;
use App\Models\School;
use App\Models\SchoolYear;

class RegistrationFeature implements IRegistration
{
    private static string $keyToSearch;

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

    /**
     * @inheritDoc
     */
    public static function getListByDate(
        string $date,
        int|null $sectionId,
        int|null $optionId,
        int|null $classRoomId,
        bool $isOld,
        string $q,
        string $sortBy,
        bool   $sortAsc,
        int $per_page = 20
    ): mixed {
        $filters = [
            'section_id' => $sectionId,
            'option_id' => $optionId,
            'class_room_id' => $classRoomId,
            'is_old' => $isOld,
            'sort_by' => $sortBy,
            'sort_asc' => $sortAsc,
        ];
        return Registration::query()
            ->whereDate("registrations.created_at", $date)
            ->filter($filters)
            ->paginate($per_page);
    }

    /**
     * @inheritDoc
     */
    public static function getListByMonth(
        string $month,
        int|null $sectionId,
        int|null $optionId,
        int|null $classRoomId,
        bool $isOld,
        string $q,
        string $sortBy,
        bool   $sortAsc,
        int $per_page = 20
    ): mixed {
        $filters = [
            'section_id' => $sectionId,
            'option_id' => $optionId,
            'class_room_id' => $classRoomId,
            'is_old' => $isOld,
            'sort_by' => $sortBy,
            'sort_asc' => $sortAsc,
        ];
        return Registration::query()
            ->whereMonth("registrations.created_at", $month)
            ->filter($filters)
            ->paginate($per_page);
    }

    /**
     * @inheritDoc
     */
    public static function getTotalAmountByDate(
        string $date,
        int|null $sectionId,
        int|null $optionId,
        int|null $classRoomId,
        bool $isOld
    ): int {
        $total = 0;
        $filters = [
            'section_id' => $sectionId,
            'option_id' => $optionId,
            'class_room_id' => $classRoomId,
            'is_old' => $isOld,
        ];
        $registrations = Registration::query()
            ->whereDate("registrations.created_at", $date)
            ->filterNotSorted($filters)
            ->get();
        foreach ($registrations as $registration) {
            $total  += $registration->registrationFee->amount;
        }
        return $total;
    }

    /**
     * @inheritDoc
     */
    public static function getTotalAmountByMonth(
        string $month,
        int|null $sectionId,
        int|null $optionId,
        int|null $classRoomId,
        bool $isOld,
    ): int {
        $total = 0;
        $filters = [
            'section_id' => $sectionId,
            'option_id' => $optionId,
            'class_room_id' => $classRoomId,
            'is_old' => $isOld,
        ];
        $registrations =  Registration::query()
            ->whereMonth("registrations.created_at", $month)
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
    public static function getTotalCountByDate(
        string $date,
        int|null $sectionId,
        int|null $optionId,
        int|null $classRoomId,
        bool $isOld = false
    ): int {
        $filters = [
            'section_id' => $sectionId,
            'option_id' => $optionId,
            'class_room_id' => $classRoomId,
            'is_old' => $isOld,
        ];
        return Registration::query()
            ->whereDate("registrations.created_at", $date)
            ->filterNotSorted($filters)
            ->count();
    }

    /**
     * @inheritDoc
     */
    public static function getTotalCountByMonth(
        string $month,
        int|null $sectionId,
        int|null $optionId,
        int|null $classRoomId,
        bool $isOld = false
    ): int {
        $filters = [
            'section_id' => $sectionId,
            'option_id' => $optionId,
            'class_room_id' => $classRoomId,
            'is_old' => $isOld,
        ];
        return Registration::query()
            ->whereMonth("registrations.created_at", $month)
            ->filterNotSorted($filters)
            ->count();
    }
    /**
     * @inheritDoc
     */
    public static function getListByClassRoom(
        int $class_room_id,
        string $sortBy,
        bool   $sortAsc,
    ): mixed {
        return Registration::query()
            ->join('students', 'registrations.student_id', 'students.id')
            ->where('registrations.class_room_id', $class_room_id)
            ->where('registrations.abandoned', false)
            ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->with(['student'])
            ->select('students.name', 'students.gender', 'registrations.*')
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')
            ->get();
    }
    /**
     * @inheritDoc
     */
    public static function getCountByClassRoom(int $class_room_id, $month = ""): float|int
    {

        return Registration::query()
            ->where('class_room_id', $class_room_id)
            ->where('abandoned', false)
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->when(
                $month,
                function ($query, $f) {
                    return $query->whereMonth('created_at', $f);
                }
            )
            ->count();
    }
    /**
     * @inheritDoc
     */
    public static function getListByResponsible(?ResponsibleStudent $responsibleStudent): mixed
    {
        return  Registration::query()
            ->join('students', 'registrations.student_id', 'students.id')
            ->join('responsible_students', 'responsible_students.id', 'students.responsible_student_id')
            ->where('responsible_students.id', $responsibleStudent->id ?? null)
            ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->select('registrations.*')
            ->with(['student', 'registrationFee', 'classRoom', 'schoolYear'])
            ->get();
    }
    /**
     * @inheritDoc
     */
    public static function getListAllInscription(
        string $q,
        int|null $optionId,
        string $sortBy,
        bool $sortAsc,
        int $per_page = 20
    ): mixed {
        SELF::$keyToSearch = $q;
        return  Registration::query()
            ->join('students', 'registrations.student_id', 'students.id')
            ->join('responsible_students', 'students.responsible_student_id', 'responsible_students.id')
            ->join('class_rooms', 'registrations.class_room_id', 'class_rooms.id')
            ->join('options', 'options.id', 'class_rooms.option_id')
            ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->when($q, function ($query) {
                return $query->where(function ($query) {
                    return $query->where('students.name', 'like', '%' . SELF::$keyToSearch . '%')
                        ->orWhere('students.place_of_birth', 'like', '%' . SELF::$keyToSearch . '%')
                        ->orWhere('students.date_of_birth', 'like', '%' . SELF::$keyToSearch . '%');
                });
            })
            ->when(
                $optionId,
                function ($query, $f) {
                    return $query->where('class_rooms.option_id', $f);
                }
            )
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->select('registrations.*')
            ->with(['student', 'registrationFee', 'classRoom', 'schoolYear'])
            ->paginate($per_page);
    }
}
