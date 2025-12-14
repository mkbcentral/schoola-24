<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'email',
        'phone',
        'logo',
        'app_status',
        'school_status',
    ];

    /**
     * Get all of the users for the School
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all of the sections for the School
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    /**
     * Get all of the responsibleStudents for the School
     */
    public function responsibleStudents(): HasMany
    {
        return $this->hasMany(ResponsibleStudent::class);
    }

    public static function DEFAULT_SCHOOL_ID(): int
    {
        return Auth::user()->school->id ?? 0;
    }

    public static function DEFAULT_SCHOOL_NAME(): string
    {
        return Auth::user()->school->name;
    }
}
