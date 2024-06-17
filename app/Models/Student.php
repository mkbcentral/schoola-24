<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'responsible_student_id'
    ];
    /**
     * Get the user that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function responsibleStudent(): BelongsTo
    {
        return $this->belongsTo(ResponsibleStudent::class, 'responsible_student_id',);
    }
}
