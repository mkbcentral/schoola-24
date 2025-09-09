<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegistrationDerogation extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'start_date',
        'end_date',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
