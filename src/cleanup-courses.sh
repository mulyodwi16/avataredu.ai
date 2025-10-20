#!/bin/sh
# Cleanup old SCORM test courses from database

php artisan tinker << 'EOF'
use App\Models\Course;

// Find all SCORM courses
$scormCourses = Course::where('content_type', 'scorm')->get();

echo "Found " . $scormCourses->count() . " SCORM courses\n\n";

if ($scormCourses->count() > 1) {
    // Keep only the latest 2
    $toDelete = $scormCourses->sortByDesc('created_at')->slice(2);
    
    echo "Deleting " . $toDelete->count() . " old test SCORM courses...\n";
    
    foreach ($toDelete as $course) {
        echo "- Deleting: " . $course->title . " (ID: " . $course->id . ")\n";
        $course->delete();
    }
    
    echo "\nCleanup complete!\n";
} else {
    echo "Only " . $scormCourses->count() . " SCORM courses found, keeping all.\n";
}

// Show remaining SCORM courses
$remaining = Course::where('content_type', 'scorm')->get(['id', 'title', 'created_at']);
echo "\nRemaining SCORM courses:\n";
foreach ($remaining as $course) {
    echo "- " . $course->title . " (ID: " . $course->id . ", Created: " . $course->created_at->format('Y-m-d H:i') . ")\n";
}

echo "\nDatabase cleanup done!\n";
EOF
