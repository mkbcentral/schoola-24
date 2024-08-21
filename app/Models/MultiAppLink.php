<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MultiAppLink extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'icon',
        'user_id'
    ];
    /**
     * Get the user that owns the SingleAppLink
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all of the subLinks for the MultiAppLink
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subLinks(): HasMany
    {
        return $this->hasMany(SubLink::class);
    }

    /**
     * The users that belong to the MultiAppLink
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('id');
    }
}
