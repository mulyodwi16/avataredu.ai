<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Department;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class InstitutionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create test institution
        $institution = Institution::create([
            'name' => 'Universitas Demo',
            'code' => 'UDEMO',
            'type' => 'university',
            'city' => 'Jakarta',
            'country' => 'Indonesia',
            'email' => 'info@udemo.test',
            'is_active' => true
        ]);

        // Create departments
        $departments = [
            [
                'name' => 'Teknik Informatika',
                'code' => 'TI'
            ],
            [
                'name' => 'Sistem Informasi',
                'code' => 'SI'
            ]
        ];

        foreach ($departments as $dept) {
            Department::create([
                'institution_id' => $institution->id,
                'name' => $dept['name'],
                'code' => $dept['code']
            ]);
        }

        // Get some existing users and courses
        $users = User::take(5)->get();
        $courses = Course::take(3)->get();

        // Assign users to institution
        foreach ($users as $index => $user) {
            $user->institutions()->attach($institution->id, [
                'department_id' => $institution->departments->random()->id,
                'student_id' => 'STD' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'status' => 'active'
            ]);
        }

        // Assign courses to institution
        foreach ($courses as $course) {
            $institution->courses()->attach($course->id, [
                'department_id' => $institution->departments->random()->id,
                'is_mandatory' => fake()->boolean,
                'available_from' => now(),
                'available_until' => now()->addYear()
            ]);
        }
    }
}