<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CourseController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of courses.
     */
    public function index()
    {
        $courses = Course::with(['category', 'creator'])
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(12);

        return view('pages.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        $this->authorize('create', Course::class);
        $categories = Category::all();
        return view('pages.courses.create', compact('categories'));
    }

    /**
     * Store a newly created course.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Course::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'thumbnail' => 'required|image|max:2048',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'required|string',
            'learning_outcomes' => 'required|array',
            'requirements' => 'required|array',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('courses', 'public');
            $validated['thumbnail'] = $path;
        }

        $validated['creator_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['status'] = 'draft';

        $course = Course::create($validated);

        return redirect()
            ->route('creator.courses.edit', $course)
            ->with('success', 'Course created successfully! Now you can add content.');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $course->load(['category', 'creator']);
        return view('pages.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the course.
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        $categories = Category::all();
        return view('pages.courses.edit', compact('course', 'categories'));
    }

    /**
     * Update the specified course.
     */
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'required|string',
            'learning_outcomes' => 'required|array',
            'requirements' => 'required|array',
            'status' => 'required|in:draft,published',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $path = $request->file('thumbnail')->store('courses', 'public');
            $validated['thumbnail'] = $path;
        } else {
            // Keep existing thumbnail if not changed
            $validated['thumbnail'] = $course->thumbnail;
        }

        if ($validated['status'] === 'published' && !$course->published_at) {
            $validated['published_at'] = now();
        }

        $course->update($validated);

        return redirect()
            ->route('creator.courses.edit', $course)
            ->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified course.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return redirect()
            ->route('creator.courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}