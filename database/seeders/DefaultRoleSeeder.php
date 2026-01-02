<?php

namespace Database\Seeders;

use App\Enums\RoleType;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //make ROOT and APP_ADMIN roles
        Role::create(
            ['name' => RoleType::ROOT],
        );
        $this->command->info('ROOT role created');
        Role::create(
            ['name' => RoleType::APP_ADMIN],
        );

        $this->command->info('Default roles created: ROOT and APP_ADMIN');
    }
}
