<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Show the course learning page
     */
    public function learn(Course $course)
    {
        // Check if user is enrolled in the course
        $enrollment = Auth::user()->enrollments()
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return redirect()->route('dashboard')->with('error', 'You are not enrolled in this course.');
        }

        // Get course with chapters and lessons
        $course->load([
            'chapters' => function ($query) {
                $query->orderBy('order');
            },
            'chapters.lessons' => function ($query) {
                $query->orderBy('order');
            }
        ]);

        // Get user's lesson progress (only if course has lessons)
        $lessonProgress = collect([]);
        if ($course->chapters && $course->chapters->count() > 0) {
            $lessonIds = $course->chapters->flatMap->lessons->pluck('id');
            if ($lessonIds->count() > 0) {
                $lessonProgress = Auth::user()->lessonProgress()
                    ->whereIn('lesson_id', $lessonIds)
                    ->get()
                    ->keyBy('lesson_id');
            }
        }

        return view('pages.course-learn', compact('course', 'enrollment', 'lessonProgress'));
    }
}