<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::where('email', 'admin@avataredu.ai')->first();
        }

        if (!$admin) {
            echo "Admin user not found. Please run AdminUserSeeder first.\n";
            return;
        }

        $categories = Category::all();
        if ($categories->isEmpty()) {
            echo "Categories not found. Please run CategorySeeder first.\n";
            return;
        }

        $courses = [
            [
                'title' => 'Laravel Fundamentals for Beginners',
                'description' => 'Learn the basics of Laravel framework from scratch. This course covers routing, views, controllers, models, and database operations.',
                'price' => 299000,
                'level' => 'beginner',
                'category' => 'Programming',
                'is_published' => true,
            ],
            [
                'title' => 'React.js Complete Guide',
                'description' => 'Master React.js from basic concepts to advanced patterns. Build real-world applications with modern React techniques.',
                'price' => 499000,
                'level' => 'intermediate',
                'category' => 'Programming',
                'is_published' => true,
            ],
            [
                'title' => 'UI/UX Design Principles',
                'description' => 'Learn fundamental design principles and create beautiful, user-friendly interfaces using modern design tools.',
                'price' => 399000,
                'level' => 'beginner',
                'category' => 'Design',
                'is_published' => true,
            ],
            [
                'title' => 'Digital Marketing Strategy',
                'description' => 'Comprehensive course on digital marketing including SEO, social media marketing, email marketing, and analytics.',
                'price' => 599000,
                'level' => 'intermediate',
                'category' => 'Marketing',
                'is_published' => true,
            ],
            [
                'title' => 'Python for Data Science',
                'description' => 'Learn Python programming specifically for data analysis, visualization, and machine learning applications.',
                'price' => 799000,
                'level' => 'intermediate',
                'category' => 'Programming',
                'is_published' => true,
            ],
            [
                'title' => 'Business Management Basics',
                'description' => 'Essential business management skills including leadership, project management, and team coordination.',
                'price' => 449000,
                'level' => 'beginner',
                'category' => 'Business',
                'is_published' => false, // Draft course
            ],
        ];

        foreach ($courses as $courseData) {
            $category = $categories->firstWhere('name', $courseData['category']);

            if ($category) {
                Course::create([
                    'title' => $courseData['title'],
                    'slug' => Str::slug($courseData['title']),
                    'description' => $courseData['description'],
                    'price' => $courseData['price'],
                    'level' => $courseData['level'],
                    'category_id' => $category->id,
                    'creator_id' => $admin->id,
                    'is_published' => $courseData['is_published'],
                    'duration_hours' => rand(5, 40),
                    'total_chapters' => rand(3, 12),
                    'total_lessons' => rand(15, 50),
                    'enrolled_count' => rand(0, 150),
                    'average_rating' => rand(35, 50) / 10, // 3.5 - 5.0
                ]);
            }
        }
    }
}
