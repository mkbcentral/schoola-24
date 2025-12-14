<?php

namespace App\Domain\Features\Configuration;

use App\Domain\Contract\Configuration\ISchoolDataConfig;
use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\School;
use App\Models\Section;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SchoolDataFeature implements ISchoolDataConfig
{
    /**
     * {@inheritDoc}
     */
    public static function getClassRoomList(
        ?int $option_filer,
        ?string $sortBy,
        ?bool $sortAsc,
        int $per_page = 10,
    ): LengthAwarePaginator {
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
            ->paginate($per_page);
    }

    /**
     * {@inheritDoc}
     */
    public static function getOptionList(int $per_page = 10): LengthAwarePaginator
    {
        return Option::query()
            ->join('sections', 'options.section_id', 'sections.id')
            ->where('sections.school_id', School::DEFAULT_SCHOOL_ID())
            ->with('section')
            ->select('options.*')
            ->paginate($per_page);
    }

    /**
     * {@inheritDoc}
     */
    public static function getSectionList(): Collection
    {
        return Section::query()
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->get();
    }

    public static function getFirstOption(): Option
    {
        return Option::query()
            ->join('sections', 'options.section_id', 'sections.id')
            ->where('sections.school_id', School::DEFAULT_SCHOOL_ID())
            ->with('section')
            ->select('options.*')
            ->first();
    }
}
