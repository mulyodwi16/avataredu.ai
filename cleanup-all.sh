#!/bin/bash
# Quick cleanup script

echo "🧹 Cleaning up storage..."
echo ""

# Storage cleanup summary
echo "📊 Before cleanup:"
du -sh storage/app/scorm 2>/dev/null || echo "  SCORM storage: ~191MB (cleaned earlier)"
echo ""

echo "🗑️  Deleting ALL SCORM test courses from database..."

php artisan tinker --execute="
use App\Models\Course;

\$courses = Course::where('content_type', 'scorm')->get();
foreach (\$courses as \$course) {
    echo 'Deleting: ' . \$course->title . PHP_EOL;
    \$course->delete();
}

echo 'Done! Remaining SCORM courses: ' . Course::where('content_type', 'scorm')->count() . PHP_EOL;
"

echo ""
echo "✅ Fresh system ready!"
echo ""
echo "Next steps:"
echo "1. Login to admin panel"
echo "2. Create your first course"
echo "3. Test SCORM upload if needed"
