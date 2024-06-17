<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\School;
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

        $school = School::create(['SCHOOL TEST']);

        $sections = [
            ['name' => 'MATERNELLE', 'school_id' => $school->id],
            ['name' => 'PRIMAIRE', 'school_id' => $school->id],
            ['name' => 'SECONDAIRE', 'school_id' => $school->id],
        ];
        Section::insert($sections);
        $options = [
            ['name' => 'MATERNELLE', 'section_id' => 1],
            ['name' => 'PRIMAIRE', 'section_id' => 2],
            ['name' => 'Education de Base', 'abbreviation' => 'EB', 'section_id' => 3],
            ['name' => 'Pédagogie Générale', 'abbreviation' => 'PH', 'section_id' => 3],
            ['name' => 'Scientifique', 'section_id' => 3],
        ];
        Option::insert($options);

        Option::factory(50)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'school_id' => $school->id
        ]);
    }
}
