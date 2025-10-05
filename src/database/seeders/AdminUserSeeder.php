<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create super admin user
        User::updateOrCreate(
            ['email' => 'superadmin@avataredu.ai'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
                'bio' => 'Super Administrator for AvatarEdu.ai',
            ]
        );

        // Create regular admin user  
        User::updateOrCreate(
            ['email' => 'admin@avataredu.ai'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'bio' => 'Administrator for AvatarEdu.ai',
            ]
        );

        // Create regular user for testing
        User::updateOrCreate(
            ['email' => 'user@avataredu.ai'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'bio' => 'Regular user for testing purposes',
            ]
        );
    }
}