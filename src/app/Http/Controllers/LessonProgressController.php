<?php

namespace App\Http\Controllers;

use App\Models\CourseLesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonProgressController extends Controller
{
    // Middleware akan ditangani di route level

    /**
     * Mark lesson as complete
     */
    public function markComplete(CourseLesson $lesson)
    {
        // Verify user has access to this lesson (must be enrolled in course)
        $enrollment = Auth::user()->enrollments()
            ->where('course_id', $lesson->chapter->course_id)
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'You must be enrolled in this course to mark lessons as complete.'
            ], 403);
        }

        // Find or create lesson progress
        $progress = LessonProgress::firstOrCreate([
            'user_id' => Auth::id(),
            'lesson_id' => $lesson->id,
        ], [
            'total_duration' => $lesson->duration ?? 0,
            'watched_duration' => 0,
            'last_watched_position' => 0,
            'is_completed' => false
        ]);

        // Mark as completed
        $progress->markAsCompleted();

        return response()->json([
            'success' => true,
            'message' => 'Lesson marked as complete!',
            'progress' => $progress->progress_percentage
        ]);
    }

    /**
     * Mark lesson as incomplete
     */
    public function markIncomplete(CourseLesson $lesson)
    {
        // Verify user has access to this lesson (must be enrolled in course)
        $enrollment = Auth::user()->enrollments()
            ->where('course_id', $lesson->chapter->course_id)
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'You must be enrolled in this course to update lesson progress.'
            ], 403);
        }

        // Find lesson progress
        $progress = LessonProgress::where([
            'user_id' => Auth::id(),
            'lesson_id' => $lesson->id,
        ])->first();

        if ($progress) {
            $progress->update([
                'is_completed' => false,
                'completed_at' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lesson marked as incomplete.',
            'progress' => $progress ? $progress->progress_percentage : 0
        ]);
    }

    /**
     * Update lesson progress (for video watching progress)
     */
    public function updateProgress(Request $request, CourseLesson $lesson)
    {
        $request->validate([
            'watched_duration' => 'required|integer|min:0',
            'last_watched_position' => 'required|integer|min:0',
            'total_duration' => 'nullable|integer|min:0'
        ]);

        // Verify user has access to this lesson
        $enrollment = Auth::user()->enrollments()
            ->where('course_id', $lesson->chapter->course_id)
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'You must be enrolled in this course to track progress.'
            ], 403);
        }

        // Find or create lesson progress
        $progress = LessonProgress::firstOrCreate([
            'user_id' => Auth::id(),
            'lesson_id' => $lesson->id,
        ], [
            'total_duration' => $request->total_duration ?? $lesson->duration ?? 0,
            'watched_duration' => 0,
            'last_watched_position' => 0,
            'is_completed' => false
        ]);

        // Update progress
        $progress->update([
            'watched_duration' => $request->watched_duration,
            'last_watched_position' => $request->last_watched_position,
            'total_duration' => $request->total_duration ?? $progress->total_duration,
        ]);

        // Auto-mark as complete if watched 95% or more
        if ($progress->progress_percentage >= 95 && !$progress->is_completed) {
            $progress->markAsCompleted();
        }

        return response()->json([
            'success' => true,
            'progress' => $progress->progress_percentage,
            'is_completed' => $progress->is_completed
        ]);
    }
}
