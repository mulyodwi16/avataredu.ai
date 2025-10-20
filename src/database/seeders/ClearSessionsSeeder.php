<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearSessionsSeeder extends Seeder
{
    /**
     * Clear all existing sessions for security
     */
    public function run(): void
    {
        // Clear all sessions from database
        DB::table('sessions')->truncate();

        // Clear cache sessions if using cache driver
        if (config('session.driver') === 'database') {
            $this->command->info('Database sessions cleared.');
        }
    }
}