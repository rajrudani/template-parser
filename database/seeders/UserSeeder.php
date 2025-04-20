<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $principalRole = Role::firstOrCreate(['name' => 'principal']);

        // Create Super Admin User
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('superadmin')    
        ]);
        $superAdmin->assignRole($superAdminRole);

        $principal1 = User::create([
            'name' => 'Principal One',
            'email' => 'principal1@example.com',
            'password' => Hash::make('principal1')
        ]);
        $principal1->assignRole($principalRole);

        $principal2 = User::create([
            'name' => 'Principal Two',
            'email' => 'principal2@example.com',
            'password' => Hash::make('principal2')
        ]);
        $principal2->assignRole($principalRole);
    }
}
