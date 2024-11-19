<?php

namespace Database\Factories;

use App\Models\Salary;
use App\Models\School;
use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SalaryFactory extends Factory
{
    protected $model = Salary::class;

    public function definition(): array
    {
        return [
            'month' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'school_id' => School::factory(),
            'school_year_id' => SchoolYear::factory(),
        ];
    }
}
