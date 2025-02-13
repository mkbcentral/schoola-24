<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherRecipe extends Model
{
    protected $fillable = [
        'description',
        'amount',
        'user_id',
        'created_at'
    ];
}
