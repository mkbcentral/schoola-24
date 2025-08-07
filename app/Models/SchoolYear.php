<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
        'school_id',
        'user_id'
    ];

    //cast is_active to boolean
    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get all of the registrations for the SchoolYear
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public static function DEFAULT_SCHOOL_YEAR_ID(): int
    {
        $workOnYear = Auth::user()->work_on_year;
        $schoolYear = SchoolYear::query()
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->when(
                $workOnYear,
                fn($query, $workOnYear) => $query->where('id', $workOnYear),
                fn($query) => $query->where('is_active', true)
            )
            ->first();
        return $schoolYear->id;
    }

    public static function DEFAULT_SCHOOL_YEAR_NAME(): string
    {
        $workOnYear = Auth::user()->work_on_year;
        $schoolYear = SchoolYear::query()
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->when(
                $workOnYear,
                fn($query, $workOnYear) => $query->where('id', $workOnYear),
                fn($query) => $query->where('is_active', true)
            )
            ->first();
        return $schoolYear->name;
    }
}
