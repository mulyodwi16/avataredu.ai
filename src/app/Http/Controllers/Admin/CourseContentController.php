<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\CourseLesson;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CourseContentController extends Controller
{
    /**
     * Display course content management page
     */
    public function index(Course $course)
    {
        $chapters = $course->chapters()->with('lessons')->orderBy('order')->get();

        $html = view('admin.partials.course-content-management', compact('course', 'chapters'))->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Store a new chapter
     */
    public function storeChapter(Request $request, Course $course): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'order' => 'required|integer|min:1'
            ]);

            $chapter = new CourseChapter();
            $chapter->course_id = $course->id;
            $chapter->title = $request->title;
            $chapter->description = $request->description;
            $chapter->order = $request->order;
            $chapter->save();

            return response()->json([
                'success' => true,
                'message' => 'Chapter created successfully',
                'chapter' => $chapter
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Chapter creation error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create chapter'], 500);
        }
    }

    /**
     * Update chapter
     */
    public function updateChapter(Request $request, Course $course, CourseChapter $chapter): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'order' => 'required|integer|min:1'
            ]);

            $chapter->title = $request->title;
            $chapter->description = $request->description;
            $chapter->order = $request->order;
            $chapter->save();

            return response()->json([
                'success' => true,
                'message' => 'Chapter updated successfully',
                'chapter' => $chapter
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Chapter update error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update chapter'], 500);
        }
    }

    /**
     * Delete chapter
     */
    public function deleteChapter(Course $course, CourseChapter $chapter): JsonResponse
    {
        try {
            // Delete all lessons in this chapter first
            foreach ($chapter->lessons as $lesson) {
                // Delete video file if exists
                if ($lesson->video_url) {
                    $videoPath = str_replace(asset('storage/'), '', $lesson->video_url);
                    \Storage::disk('public')->delete($videoPath);
                }
            }

            $chapter->delete();

            return response()->json([
                'success' => true,
                'message' => 'Chapter and its lessons deleted successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Chapter deletion error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete chapter'], 500);
        }
    }

    /**
     * Store a new lesson
     */
    public function storeLesson(Request $request, Course $course, CourseChapter $chapter): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'nullable|string',
                'order' => 'required|integer|min:1',
                'video' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv|max:102400', // max 100MB
                'duration' => 'nullable|integer|min:1' // duration in seconds
            ]);

            $lessonData = [
                'chapter_id' => $chapter->id,
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'order' => $request->input('order'),
                'duration' => $request->input('duration')
            ];

            // Handle video upload
            if ($request->hasFile('video')) {
                $path = $request->file('video')->store('course-videos', 'public');
                $lessonData['video_url'] = asset('storage/' . $path);
            }

            $lesson = CourseLesson::create($lessonData);

            return response()->json([
                'success' => true,
                'message' => 'Lesson created successfully',
                'lesson' => $lesson
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Lesson creation error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create lesson'], 500);
        }
    }

    /**
     * Update lesson
     */
    public function updateLesson(Request $request, CourseLesson $lesson): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'nullable|string',
                'order' => 'required|integer|min:1',
                'video' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv|max:102400', // max 100MB
                'duration' => 'nullable|integer|min:1' // duration in seconds
            ]);

            $updateData = [
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'order' => $request->input('order'),
                'duration' => $request->input('duration')
            ];

            // Handle video upload
            if ($request->hasFile('video')) {
                // Delete old video if exists
                if ($lesson->video_url) {
                    $oldPath = str_replace(asset('storage/'), '', $lesson->video_url);
                    \Storage::disk('public')->delete($oldPath);
                }

                $path = $request->file('video')->store('course-videos', 'public');
                $updateData['video_url'] = asset('storage/' . $path);
            }

            $lesson->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Lesson updated successfully',
                'lesson' => $lesson
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Lesson update error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update lesson'], 500);
        }
    }

    /**
     * Delete lesson
     */
    public function deleteLesson(CourseLesson $lesson): JsonResponse
    {
        try {
            // Delete video file if exists
            if ($lesson->video_url) {
                $videoPath = str_replace(asset('storage/'), '', $lesson->video_url);
                \Storage::disk('public')->delete($videoPath);
            }

            $lesson->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lesson deleted successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Lesson deletion error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete lesson'], 500);
        }
    }
}