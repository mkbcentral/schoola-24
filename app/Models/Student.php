<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'responsible_student_id'
    ];
    /**
     * Get the user that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function responsibleStudent(): BelongsTo
    {
        return $this->belongsTo(ResponsibleStudent::class, 'responsible_student_id',);
    }

    /**
     * @return int
     */
    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->attributes['date_of_birth'])->age;
    }

    /**
     * Get the registration associated with the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function registration(): HasOne
    {
        return $this->hasOne(Registration::class);
    }

    public function getFormattedAg()
    {
        return $this->age <= 1 ? $this->age . ' An' : $this->age . ' Ans';
    }

    public function getLastClassRoomName()
    {
        $lastSchoolYear = SchoolYear::where('is_last_year', true)->first();
        $registration = Registration::query()
            ->where('student_id', $this->id)
            ->where('school_year_id', $lastSchoolYear->id)
            ->first();
        return $registration ? $registration->classRoom->getOriginalClassRoomName() : '';
    }
}
