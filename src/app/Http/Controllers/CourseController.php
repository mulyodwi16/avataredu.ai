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
        // Debug logging
        \Log::info('Learn page accessed', [
            'course_id' => $course->id,
            'content_type' => $course->content_type,
            'scorm_package_path' => $course->scorm_package_path,
            'is_scorm' => $course->content_type === 'scorm'
        ]);

        // Check if user is enrolled in the course
        $enrollment = Auth::user()->enrollments()
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return redirect()->route('dashboard')->with('error', 'You are not enrolled in this course.');
        }

        // If it's a SCORM course (single or multi-chapter), display it with chapters sidebar
        $hasScormChapters = $course->scormChapters()->exists();
        $hasRegularChapters = $course->chapters()->exists();

        \Log::info('SCORM check', [
            'course_id' => $course->id,
            'content_type' => $course->content_type,
            'is_scorm_or_multi' => in_array($course->content_type, ['scorm', 'scorm_multi']),
            'has_scorm_package_path' => !empty($course->scorm_package_path),
            'has_scorm_chapters' => $hasScormChapters,
            'has_regular_chapters' => $hasRegularChapters
        ]);

        if (
            in_array($course->content_type, ['scorm', 'scorm_multi']) &&
            ($course->scorm_package_path || $hasScormChapters || $hasRegularChapters)
        ) {
            \Log::info('Serving SCORM course', ['course_id' => $course->id]);

            // Load SCORM chapters for this course (prefer scormChapters if they exist, otherwise load regular chapters)
            $course->load([
                'scormChapters' => function ($query) {
                    $query->orderBy('order');
                },
                'chapters' => function ($query) {
                    $query->orderBy('order');
                    $query->with([
                        'lessons' => function ($q) {
                            $q->orderBy('order');
                        }
                    ]);
                }
            ]);

            return view('pages.course-learn-scorm', compact('course', 'enrollment'));
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