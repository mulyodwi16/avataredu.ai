<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Course;
use App\Models\CourseChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPagesController extends Controller
{
    /**
     * Get all pages (for API)
     */
    public function index()
    {
        $pages = Page::orderBy('created_at', 'desc')->get();
        return response()->json([
            'html' => view('admin.partials.pages-content', compact('pages'))->render()
        ]);
    }

    /**
     * Show create page form
     */
    public function create()
    {
        $courses = Course::orderBy('title')->get();
        return response()->json([
            'html' => view('admin.pages.create-page', compact('courses'))->render()
        ]);
    }

    /**
     * Store a newly created page
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        // Generate slug from title
        $slug = Str::slug($validated['title']);

        // Make sure slug is unique
        $existingSlug = Page::where('slug', $slug)->count();
        if ($existingSlug > 0) {
            $slug = $slug . '-' . now()->timestamp;
        }

        $validated['slug'] = $slug;

        Page::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Page created successfully!',
            'redirect' => '/admin/pages'
        ], 201);
    }

    /**
     * Show edit page form
     */
    public function edit(Page $page)
    {
        $courses = Course::orderBy('title')->get();
        $chapters = $page->course_id ? CourseChapter::where('course_id', $page->course_id)->get() : collect();

        return response()->json([
            'html' => view('admin.pages.edit-page', compact('page', 'courses', 'chapters'))->render()
        ]);
    }

    /**
     * Update the page
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        // Update slug if title changed
        if ($validated['title'] !== $page->title) {
            $slug = Str::slug($validated['title']);

            // Make sure slug is unique (excluding current page)
            $existingSlug = Page::where('slug', $slug)
                ->where('id', '!=', $page->id)
                ->count();

            if ($existingSlug > 0) {
                $slug = $slug . '-' . now()->timestamp;
            }

            $validated['slug'] = $slug;
        }

        $page->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Page updated successfully!',
            'redirect' => '/admin/pages'
        ]);
    }

    /**
     * Delete a page
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully!'
        ]);
    }

    /**
     * Get chapters for a specific course (API endpoint)
     */
    public function getCourseChapters(Course $course)
    {
        try {
            $chapters = CourseChapter::where('course_id', $course->id)
                ->orderBy('order')
                ->get()
                ->map(function ($chapter) {
                    return [
                        'id' => $chapter->id,
                        'title' => $chapter->title
                    ];
                });

            return response()->json([
                'success' => true,
                'chapters' => $chapters
            ]);
        } catch (\Exception $e) {
            \Log::error('Get course chapters error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error loading chapters: ' . $e->getMessage()
            ], 500);
        }
    }
}
