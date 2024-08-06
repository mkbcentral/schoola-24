<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GiveUpStudent extends Model
{
    use HasFactory;

    protected $fillable = ['registration_id', 'month'];

    /**
     * Get the registration that owns the GiveUpStudent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id',);
    }
}
