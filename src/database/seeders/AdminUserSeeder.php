<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@avataredu.ai'], // kalau sudah ada, update
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'), // password default
            ]
        );
    }
}