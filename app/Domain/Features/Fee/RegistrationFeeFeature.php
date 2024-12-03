<?php

namespace App\Domain\Features\Fee;

use App\Domain\Contract\Fee\IRegistrationFee;
use App\Models\RegistrationFee;
use App\Models\School;
use App\Models\SchoolYear;

class RegistrationFeeFeature implements IRegistrationFee
{
    /**
     * @inheritDoc
     */
    public static function getListRegistrationFee(
        string $q,
        int
        $option_filter,
        int $per_page = 5
    ): mixed {
        return RegistrationFee::query()
            ->join('options', 'registration_fees.option_id', '=', 'options.id')
            ->join('sections', 'options.section_id', '=', 'sections.id')
            ->where('sections.school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->when(
                $option_filter,
                function ($query, $f) {
                    return $query->where('registration_fees.option_id', $f);
                }
            )
            ->where('registration_fees.name', 'like', '%' . $q . '%')
            ->with('option')
            ->select('registration_fees.*')
            ->paginate($per_page);
    }
}
