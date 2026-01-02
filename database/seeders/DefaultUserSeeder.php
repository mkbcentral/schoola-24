<?php

namespace Database\Seeders;

use App\Enums\RoleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        //Create default user here with ROOT role
        $roleRoot = \App\Models\Role::where('name', RoleType::ROOT)->first();
        \App\Models\User::create([
            'name' => 'Root User',
            'email' => 'root@schoola.app',
            'password' => bcrypt('password'), // Change this to a secure password
            'role_id' => $roleRoot->id,
        ]);
        $this->command->info('Default ROOT user created');
    

        //Create default user here with APP_ADMIN role

        $roleAdmin   = \App\Models\Role::where('name', RoleType::APP_ADMIN)->first();
        \App\Models\User::create([  
            'name' => 'Admin User',
            'email' => 'admin@schoola.app',
            'password' => bcrypt('password'), // Change this to a secure password
            'role_id' => $roleAdmin->id,
        ]);
        $this->command->info('Default APP_ADMIN user created'); 
    }
}
