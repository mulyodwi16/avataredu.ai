<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClearAllSessions extends Command
{
    protected $signature = 'sessions:clear-all {--force : Force clear without confirmation}';
    protected $description = 'Clear all user sessions from all devices';

    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will log out all users from all devices. Continue?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $sessionDriver = config('session.driver');

        switch ($sessionDriver) {
            case 'database':
                $count = DB::table('sessions')->count();
                DB::table('sessions')->truncate();
                $this->info("Cleared {$count} database sessions.");
                break;

            case 'file':
                $sessionPath = config('session.files');
                $files = glob($sessionPath . '/sess_*');
                $count = count($files);
                foreach ($files as $file) {
                    unlink($file);
                }
                $this->info("Cleared {$count} file sessions.");
                break;

            case 'redis':
                // Clear Redis sessions
                $redis = app('redis');
                $keys = $redis->keys(config('session.cookie') . '*');
                if ($keys) {
                    $redis->del($keys);
                    $this->info("Cleared " . count($keys) . " Redis sessions.");
                } else {
                    $this->info("No Redis sessions found.");
                }
                break;

            default:
                $this->error("Session driver '{$sessionDriver}' not supported for clearing.");
                return 1;
        }

        $this->info('All sessions cleared successfully!');
        $this->info('All users will need to log in again.');

        return 0;
    }
}