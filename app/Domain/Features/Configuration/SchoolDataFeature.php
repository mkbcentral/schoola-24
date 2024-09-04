<?php

namespace App\Domain\Features\Configuration;

use App\Domain\Contract\Configuration\ISchoolDataConfig;
use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\School;
use App\Models\Section;

class SchoolDataFeature implements ISchoolDataConfig
{
    /**
     * @inheritDoc
     */
    public static function getClassRoomList(
        ?int $option_filer,
        ?string $sortBy,
        ?bool $sortAsc,
        int $per_page = 10,
    ): mixed {
        return ClassRoom::query()
            ->join('options', 'class_rooms.option_id', '=', 'options.id')
            ->join('sections', 'options.section_id', '=', 'sections.id')
            ->where('sections.school_id', School::DEFAULT_SCHOOL_ID())
            ->when(
                $option_filer,
                function ($query, $f) {
                    return $query->where('class_rooms.option_id', $f);
                }
            )
            ->with('option')
            ->select('class_rooms.*')
            //->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')
            ->paginate($per_page);
    }

    /**
     * @inheritDoc
     */
    public static function getOptionList(int $per_page = 10): mixed
    {
        return Option::query()
            ->join('sections', 'options.section_id',  'sections.id')
            ->where('sections.school_id', School::DEFAULT_SCHOOL_ID())

            ->with('section')
            ->select('options.*')
            ->paginate($per_page);
    }

    /**
     * @inheritDoc
     */
    public static function getSectionList(): mixed
    {
        return Section::query()
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->get();
    }

    public static function getOptionFirstOption(int $per_page = 10): Option
    {
        return Option::query()
            ->join('sections', 'options.section_id',  'sections.id')
            ->where('sections.school_id', School::DEFAULT_SCHOOL_ID())
            ->with('section')
            ->select('options.*')
            ->first();
    }
}
