<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseChapter;
use App\Models\CourseLesson;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnrollmentTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek jika admin user sudah ada
        $adminUser = User::where('email', 'admin@avataredu.ai')->first();
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => 'admin@avataredu.ai',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now()
            ]);
        }

        // Buat regular user untuk testing
        $testUser = User::firstOrCreate([
            'email' => 'test@avataredu.ai'
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'role' => 'user',
            'email_verified_at' => now()
        ]);

        // Buat category jika belum ada
        $category = Category::firstOrCreate([
            'name' => 'Programming'
        ], [
            'description' => 'Learn programming languages and frameworks',
            'slug' => 'programming'
        ]);

        // Buat sample courses
        $courses = [
            [
                'title' => 'Laravel 11 Complete Course',
                'description' => 'Learn Laravel from basics to advanced topics including authentication, database, API development, and deployment.',
                'price' => 299000,
                'thumbnail' => null,
                'is_published' => true
            ],
            [
                'title' => 'React.js Fundamentals',
                'description' => 'Master React.js with hands-on projects. Build modern web applications with hooks, state management, and component architecture.',
                'price' => 0, // Free course
                'thumbnail' => null,
                'is_published' => true
            ],
            [
                'title' => 'Vue.js & Nuxt.js Mastery',
                'description' => 'Complete guide to Vue.js 3 and Nuxt.js. Build full-stack applications with modern JavaScript framework.',
                'price' => 399000,
                'thumbnail' => null,
                'is_published' => true
            ]
        ];

        foreach ($courses as $courseData) {
            $course = Course::firstOrCreate([
                'title' => $courseData['title']
            ], [
                'description' => $courseData['description'],
                'price' => $courseData['price'],
                'thumbnail' => $courseData['thumbnail'],
                'is_published' => $courseData['is_published'],
                'slug' => \Illuminate\Support\Str::slug($courseData['title']),
                'category_id' => $category->id,
                'creator_id' => $adminUser->id,
                'published_at' => now()
            ]);

            // Buat chapters dan lessons untuk setiap course
            if ($course->chapters()->count() === 0) {
                for ($i = 1; $i <= 3; $i++) {
                    $chapter = CourseChapter::create([
                        'course_id' => $course->id,
                        'title' => "Chapter {$i}: " . ($i == 1 ? 'Introduction' : ($i == 2 ? 'Core Concepts' : 'Advanced Topics')),
                        'description' => "Learn the fundamentals in chapter {$i}"
                    ]);

                    // Buat 2-3 lessons per chapter
                    for ($j = 1; $j <= 2; $j++) {
                        CourseLesson::create([
                            'chapter_id' => $chapter->id,
                            'title' => "Lesson {$i}.{$j}: " . ($j == 1 ? 'Getting Started' : 'Deep Dive'),
                            'description' => "Detailed explanation of lesson {$i}.{$j}",
                            'content' => "<h3>Welcome to Lesson {$i}.{$j}</h3><p>This is sample content for testing the enrollment system. In a real course, this would contain detailed explanations, code examples, and exercises.</p>",
                            'video_url' => null,
                            'duration' => rand(300, 1800) // 5-30 minutes
                        ]);
                    }
                }
            }
        }

        $this->command->info('Enrollment test data created successfully!');
        $this->command->info('Test user: test@avataredu.ai / password');
        $this->command->info('Admin user: admin@avataredu.ai / password');
        $this->command->info('Created ' . count($courses) . ' sample courses');
    }
}
