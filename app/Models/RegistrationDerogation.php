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
        'is_monthly',
        'month_date',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_monthly' => 'boolean',
        'month_date' => 'date',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
