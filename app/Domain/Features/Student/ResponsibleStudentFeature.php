<?php

namespace App\Domain\Features\Student;

use App\Domain\Contract\Student\IResponsibleStudent;
use App\Models\ResponsibleStudent;
use App\Models\School;

class ResponsibleStudentFeature implements IResponsibleStudent
{
    private static string $keyToSearch = '';
    /**
     * @inheritDoc
     */
    public static function create(array $input): ResponsibleStudent
    {
        return  ResponsibleStudent::create($input);
    }
    /**
     * @inheritDoc
     */
    public static function delete(ResponsibleStudent $responsibleStudent): bool
    {
        return $responsibleStudent->delete();
    }
    /**
     * @inheritDoc
     */
    public static function get(int $id): ResponsibleStudent
    {
        return  ResponsibleStudent::find($id);
    }
    /**
     * @inheritDoc
     */
    public static function update(ResponsibleStudent $responsibleStudent, array $input): bool
    {
        return $responsibleStudent->update($input);
    }
    /**
     * @inheritDoc
     */
    public static function getList(
        string $q,
        string $sortBy,
        bool $sortAsc,
        int $per_page = 20
    ): mixed {
        SELF::$keyToSearch = $q;
        return ResponsibleStudent::query()
            ->when($q, function ($query) {
                return $query->where(function ($query) {
                    return $query->where('name', 'like', '%' . SELF::$keyToSearch . '%')
                        ->orWhere('phone', 'like', '%' . SELF::$keyToSearch . '%')
                        ->orWhere('other_phone', 'like', '%' . SELF::$keyToSearch . '%')
                        ->orWhere('email', 'like', '%' . SELF::$keyToSearch . '%');
                });
            })
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->with([
                'students',
                'school'
            ])
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')
            ->paginate($per_page);
    }
}
