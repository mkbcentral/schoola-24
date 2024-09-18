<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorySalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_id',
        'school_year_id'
    ];
    /**
     * Get all of the salries for the CategorySalary
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salrySatails(): HasMany
    {
        return $this->hasMany(SalaryDetail::class,);
    }
}
