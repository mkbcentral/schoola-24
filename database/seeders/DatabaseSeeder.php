<?php

namespace Database\Seeders;

use App\Enums\RoleType;
use App\Models\CategoryRegistrationFee;
use App\Models\CategorySalary;
use App\Models\Currency;
use App\Models\Rate;
use App\Models\Role;
use App\Models\School;
use App\Models\SchoolYear;
use App\Models\User;
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
        $currencies = [
            ['name' => 'USD'],
            ['name' => 'CDF'],
        ];
        Currency::insert($currencies);
        Rate::create(['amount' => 2900, 'school_id' => 1]);
        SchoolYear::create(
            [
                'name' => '2024-2025',
                'school_id' => 1,
                'is_active' => true
            ]
        );
        $role = Role::create(['name' => RoleType::ADMIN_SCHOOL, 'is_for_school' => true]);
        User::create(
            [
                'name' => 'user',
                'email' => 'user@schoola.app',
                'phone' => '0971330007',
                'password' => bcrypt('password'),
                'school_id' => $school->id,
                'role_id' => $role->id
            ]
        );
        CategoryRegistrationFee::insert([
            ['name' => 'Inscription', 'school_id' => $school->id],
            ['name' => 'Réinscription', 'school_id' => $school->idb],
        ]);
        */
        $categorySalaries = [
            ['name' => 'Salaire', 'school_id' => 1, 'school_year_id' => 1],
            ['name' => 'Prime', 'school_id' => 1, 'school_year_id' => 1],
        ];
        CategorySalary::insert($categorySalaries);
    }
}
