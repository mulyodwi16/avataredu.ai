<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    /**
     * Determine if user can create courses
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if user can update course
     */
    public function update(User $user, Course $course): bool
    {
        return $user->id === $course->creator_id || $user->isAdmin();
    }

    /**
     * Determine if user can delete course
     */
    public function delete(User $user, Course $course): bool
    {
        return $user->id === $course->creator_id || $user->isAdmin();
    }
}