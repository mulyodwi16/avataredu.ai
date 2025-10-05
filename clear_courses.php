<?php

require_once __DIR__ . '/src/vendor/autoload.php';

// Clear courses
echo "Clearing all courses...\n";

// Connect to database using Laravel's config
$app = require_once __DIR__ . '/src/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Clear courses
\App\Models\Course::query()->delete();

echo "All courses cleared successfully!\n";