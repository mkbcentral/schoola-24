<?php

namespace Database\Seeders;

use App\Models\CategoryFee;
use App\Models\ClassRoom;
use App\Models\Currency;
use App\Models\Option;
use App\Models\Rate;
use App\Models\School;
use App\Models\SchoolYear;
use App\Models\ScolarFee;
use App\Models\Section;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        $school = School::create(['name' => 'SCHOOL TEST']);

        $sections = [
            ['name' => 'MATERNELLE', 'school_id' => $school->id],
            ['name' => 'PRIMAIRE', 'school_id' => $school->id],
            ['name' => 'SECONDAIRE', 'school_id' => $school->id],
        ];
        Section::insert($sections);
        $options = [
            ['name' => 'MATERNELLE', 'abbreviation' => null, 'section_id' => 1],
            ['name' => 'PRIMAIRE', 'abbreviation' => null, 'section_id' => 2],
            ['name' => 'Education de Base', 'abbreviation' => 'EB', 'section_id' => 3],
            ['name' => 'Pédagogie Générale', 'abbreviation' => 'PH', 'section_id' => 3],
            ['name' => 'Scientifique', 'abbreviation' => null, 'section_id' => 3],
        ];
        Option::insert($options);
        ClassRoom::factory(50)->create();
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'school_id' => $school->id
        ]);

        $currencies = [
            ['name' => 'USD'],
            ['name' => 'CDF'],
        ];

        Currency::insert($currencies);
         */
        Rate::create(['amount' => 2900, 'school_id' => 1]);

        SchoolYear::create(['name' => '2024-2025', 'school_id' => 1]);

        $categories = [
            ['name' => 'Frais scolaire', 'school_year_id' => 1],
            ['name' => 'Frais bus', 'school_year_id' => 1],
            ['name' => 'Tenue EPS', 'school_year_id' => 1],
        ];
        CategoryFee::insert($categories);

        $scolaryFees = [
            [
                'name' => 'Minerval primaire',
                'amount' => 40,
                'category_fee_id' => 1,
                'section_id' => 2,
                'currency_id' => 1
            ],
            [
                'name' => 'Bus zone 1',
                'amount' => 15,
                'category_fee_id' => 2,
                'section_id' => 2,
                'currency_id' => 1
            ],
        ];
        ScolarFee::insert($scolaryFees);
    }
}
