<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'route',
        'multi_app_link_id'
    ];

    /**
     * Get the user that owns the SingleAppLink
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function multiAppLink(): BelongsTo
    {
        return $this->belongsTo(MultiAppLink::class, 'multi_app_link_id');
    }

    /**
     * The users that belong to the SubLink
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('id');
    }
}
