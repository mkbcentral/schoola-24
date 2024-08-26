<?php

namespace App\Models;

use App\Domain\Features\Registration\RegistrationFeature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassRoom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'option_id'];

    /**
     * Get the option that owns the ClassRoom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'option_id');
    }

    /**
     * Get all of the registrations for the ClassRoom
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get all of the changeClassStudent-s for the ClassRoom
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function changeClassStudents(): HasMany
    {
        return $this->hasMany(ChangeClassStudent::class);
    }

    public function getOriginalClassRoomName(): string
    {
        return $this->name . '/' . $this->option->name;
    }

    public  function getRegistrationCountForCurrentSchoolYear(string $month = ""): int|float
    {
        return RegistrationFeature::getCountAll(
            null,
            $month,
            null,
            null,
            $this->id,
            null
        );
    }
}
