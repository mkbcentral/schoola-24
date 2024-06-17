<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'section_id'];

    /**
     * Get the section that owns the Option
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(User::class, 'section_id');
    }

    /**
     * Get all of the classRooms for the Option
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classRooms(): HasMany
    {
        return $this->hasMany(ClassRoom::class);
    }
}
