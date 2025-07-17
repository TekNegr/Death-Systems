<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class RoleSeeder extends Seeder
{
    
    public function run()
    {
        // Create roles
        Role::firstOrCreate(['name' => 'DeathStar']); // Creator, SuperAdmin
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'User']);

    }
}
