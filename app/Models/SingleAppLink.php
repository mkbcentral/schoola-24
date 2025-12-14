<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SingleAppLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'route',
        'user_id',
    ];

    /**
     * The users that belong to the SingleAppLink
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('id');
    }
}
