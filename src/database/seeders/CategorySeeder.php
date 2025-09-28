<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Programming', 'slug' => 'programming', 'icon' => '💻'],
            ['name' => 'Design', 'slug' => 'design', 'icon' => '🎨'],
            ['name' => 'Business', 'slug' => 'business', 'icon' => '💼'],
            ['name' => 'Marketing', 'slug' => 'marketing', 'icon' => '📈'],
            ['name' => 'Personal Development', 'slug' => 'personal-development', 'icon' => '🌱'],
            ['name' => 'Education', 'slug' => 'education', 'icon' => '📚'],
            ['name' => 'Technology', 'slug' => 'technology', 'icon' => '🤖'],
            ['name' => 'Science', 'slug' => 'science', 'icon' => '🔬'],
            ['name' => 'Mathematics', 'slug' => 'mathematics', 'icon' => '📐'],
            ['name' => 'Languages', 'slug' => 'languages', 'icon' => '🗣️'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}