<?php

namespace App\Console\Commands;

use App\Models\Course;
use Illuminate\Console\Command;

class CleanupScormCourses extends Command
{
    protected $signature = 'scorm:cleanup {--keep=2 : Number of recent SCORM courses to keep}';
    protected $description = 'Clean up old test SCORM courses from database';

    public function handle()
    {
        $keep = (int) $this->option('keep');

        $this->info('═══════════════════════════════════════');
        $this->info('SCORM Course Cleanup');
        $this->info('═══════════════════════════════════════');
        $this->newLine();

        $scormCourses = Course::where('content_type', 'scorm')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->line('Total SCORM courses found: ' . $scormCourses->count());
        $this->newLine();

        if ($scormCourses->count() > $keep) {
            $toDelete = $scormCourses->slice($keep);
            $this->line('Deleting ' . $toDelete->count() . ' old test courses...');
            $this->line('───────────────────────────────────────');

            foreach ($toDelete as $course) {
                $this->line('❌ Deleting: ' . $course->title . ' (ID: ' . $course->id . ')');
                $course->delete();
            }

            $this->line('───────────────────────────────────────');
            $this->info('✓ Deletion complete!');
            $this->newLine();
        } else {
            $this->info('✓ Only ' . $scormCourses->count() . ' courses found, keeping all.');
            $this->newLine();
        }

        $this->line('Remaining SCORM courses:');
        $this->line('───────────────────────────────────────');

        $remaining = Course::where('content_type', 'scorm')
            ->orderBy('created_at', 'desc')
            ->get(['id', 'title', 'created_at', 'scorm_package_path']);

        if ($remaining->count() === 0) {
            $this->line('No SCORM courses.');
        } else {
            foreach ($remaining as $c) {
                $this->line('✓ ' . $c->title);
                $this->line('  ID: ' . $c->id);
                $this->line('  Path: ' . $c->scorm_package_path);
                $this->line('  Created: ' . $c->created_at->format('Y-m-d H:i:s'));
                $this->newLine();
            }
        }

        $this->info('═══════════════════════════════════════');
        $this->line('Total SCORM courses after cleanup: ' . Course::where('content_type', 'scorm')->count());
        $this->info('═══════════════════════════════════════');
    }
}
