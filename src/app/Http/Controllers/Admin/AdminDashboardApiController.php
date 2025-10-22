<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminService;
use App\Services\CourseService;
use App\Models\Course;
use App\Models\CourseScormChapter;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use ZipArchive;
use DOMDocument;

class AdminDashboardApiController extends Controller
{
    protected AdminService $adminService;
    protected CourseService $courseService;

    public function __construct(AdminService $adminService, CourseService $courseService)
    {
        $this->adminService = $adminService;
        $this->courseService = $courseService;
    }

    /**
     * Display admin courses index
     */
    public function index()
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Show create course form
     */
    public function create()
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Show edit course form
     */
    public function edit(Course $course)
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Show create course page as standalone page
     */
    public function showCreateCourse()
    {
        try {
            $categories = Category::all();
            return view('admin.pages.create-course', compact('categories'));
        } catch (\Exception $e) {
            \Log::error('Create course page error: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Failed to load create course page');
        }
    }

    public function showCreateScormCourse()
    {
        try {
            $categories = Category::all();
            return view('admin.pages.create-scorm-course', compact('categories'));
        } catch (\Exception $e) {
            \Log::error('Create SCORM course page error: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Failed to load SCORM course page');
        }
    }

    public function showChooseCourseType()
    {
        try {
            return view('admin.pages.choose-course-type');
        } catch (\Exception $e) {
            \Log::error('Choose course type page error: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Failed to load course type selection');
        }
    }

    /**
     * Show edit course page as standalone page
     */
    public function showEditCourse(Course $course)
    {
        try {
            // Check if user owns this course or is super admin
            if (!auth()->user()->isSuperAdmin() && $course->creator_id !== auth()->id()) {
                return redirect()->route('admin.dashboard')->with('error', 'Access denied');
            }

            $categories = Category::all();
            return view('admin.pages.edit-course', compact('course', 'categories'));
        } catch (\Exception $e) {
            \Log::error('Edit course page error: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Failed to load edit course page');
        }
    }

    /**
     * Get admin page content for single page navigation
     */
    public function getContent(Request $request, string $page): JsonResponse
    {
        try {
            \Log::info('AdminDashboard API called with page: ' . $page);

            // Handle nested pages like courses/create, courses/{id}/edit
            $parts = explode('/', $page);
            $mainPage = $parts[0];

            \Log::info('Main page: ' . $mainPage . ', Parts: ' . json_encode($parts));

            switch ($mainPage) {
                case 'dashboard':
                    return $this->getDashboardContent();

                case 'courses':
                    if (count($parts) === 1) {
                        return $this->getCoursesContent($request);
                    } elseif (count($parts) === 2 && $parts[1] === 'create') {
                        \Log::info('Calling getCourseCreateContent');
                        return $this->getCourseCreateContent();
                    } elseif (count($parts) === 3 && $parts[2] === 'edit') {
                        return $this->getCourseEditContent($parts[1]);
                    } elseif (count($parts) === 3 && $parts[2] === 'content') {
                        return $this->getCourseContentManagement($parts[1]);
                    }
                    break;

                case 'users':
                    return $this->getUsersContent($request);
            }

            \Log::warning('Page not found: ' . $page);
            return response()->json(['error' => 'Page not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Admin page loading error: ' . $e->getMessage());
            return response()->json(['error' => 'Error loading content: ' . $e->getMessage()], 500);
        }
    }

    private function getDashboardContent()
    {
        try {
            $adminId = auth()->id();

            // Get accurate stats first
            $adminId = auth()->id();
            $isAdmin = auth()->user()->isAdmin();
            $isSuperAdmin = auth()->user()->isSuperAdmin();

            // Stats should count ALL courses, not just recent ones
            if ($isSuperAdmin) {
                // Super admin sees all courses
                $stats = [
                    'total_courses' => Course::count(),
                    'published_courses' => Course::where('is_published', 1)->count(),
                    'draft_courses' => Course::where('is_published', 0)->count(),
                ];
                $totalEnrollments = \App\Models\Enrollment::count();
            } else {
                // Regular admin sees only their courses  
                $stats = [
                    'total_courses' => Course::where('creator_id', $adminId)->count(),
                    'published_courses' => Course::where('creator_id', $adminId)->where('is_published', 1)->count(),
                    'draft_courses' => Course::where('creator_id', $adminId)->where('is_published', 0)->count(),
                ];
                $totalEnrollments = \App\Models\Enrollment::whereIn(
                    'course_id',
                    Course::where('creator_id', $adminId)->pluck('id')
                )->count();
            }

            // Recent courses for display (limit 3 for performance)
            $userCourses = Course::where('creator_id', $adminId)
                ->select('id', 'title', 'description', 'is_published', 'updated_at')
                ->latest()
                ->take(3)
                ->get();

            // Add enrollment stats
            $stats['total_enrollments'] = $totalEnrollments;
            $stats['total_students'] = \App\Models\User::where('role', 'user')->count();

            // No featured courses for faster loading
            $featuredCourses = collect([]);
            $allCoursesCount = $stats['total_courses'];
            $recentCourses = $userCourses;

            $html = view('admin.partials.dashboard-content', compact(
                'stats',
                'recentCourses',
                'featuredCourses',
                'allCoursesCount'
            ))->render();

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            \Log::error('Admin dashboard error: ' . $e->getMessage());
            return response()->json(['error' => 'Dashboard loading failed'], 500);
        }
    }

    private function getCoursesContent(Request $request)
    {
        try {
            $search = $request->get('search');
            $category = $request->get('category');
            $status = $request->get('status');
            $sort = $request->get('sort', 'latest');

            // Build fresh query with explicit field selection
            $adminId = auth()->id();
            $isSuperAdmin = auth()->user()->isSuperAdmin();

            // Start with base query
            $query = Course::select([
                'id',
                'title',
                'description',
                'thumbnail',
                'price',
                'is_published',
                'category_id',
                'creator_id',
                'enrolled_count',
                'duration_hours',
                'created_at',
                'updated_at'
            ])->with('creator:id,name');

            // Admin scope - use explicit where clause
            if (!$isSuperAdmin) {
                $query->where('creator_id', '=', $adminId);
            }

            // Apply filters
            if ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            }

            if ($category) {
                $query->where('category_id', $category);
            }

            if ($status) {
                if ($status === 'published') {
                    $query->where('is_published', true);
                } elseif ($status === 'draft') {
                    $query->where('is_published', false);
                }
            }

            // Apply sorting
            switch ($sort) {
                case 'title':
                    $query->orderBy('title');
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                default:
                    $query->latest();
                    break;
            }

            $courses = $query->with('category')->paginate(12);
            $categories = Category::all();

            $html = view('admin.partials.courses-content', compact(
                'courses',
                'categories',
                'search',
                'category',
                'status',
                'sort'
            ))->render();

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            \Log::error('Courses loading error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load courses'], 500);
        }
    }

    private function getUsersContent(Request $request)
    {
        try {
            // Only super admin can access user management
            if (!auth()->user()->isSuperAdmin()) {
                return response()->json(['error' => 'Access denied'], 403);
            }

            $search = $request->get('search');
            $role = $request->get('role');
            $sort = $request->get('sort', 'latest');

            // Build query with necessary fields
            $query = User::select('id', 'name', 'email', 'role', 'created_at', 'updated_at');

            // Apply filters
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            }

            if ($role && $role !== 'all') {
                $query->where('role', $role);
            }

            // Apply sorting
            switch ($sort) {
                case 'name':
                    $query->orderBy('name');
                    break;
                case 'email':
                    $query->orderBy('email');
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                default:
                    $query->latest();
                    break;
            }

            $users = $query->paginate(20);

            $html = view('admin.partials.users-content', compact(
                'users',
                'search',
                'role',
                'sort'
            ))->render();

            return response()->json(['html' => $html]);

        } catch (\Exception $e) {
            \Log::error('getUsersContent error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load users: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getCourseCreateContent()
    {
        try {
            \Log::info('getCourseCreateContent called');

            $categories = Category::all();
            \Log::info('Categories loaded: ' . $categories->count());

            $html = view('admin.partials.course-create-content', compact('categories'))->render();
            \Log::info('View rendered successfully');

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            \Log::error('Course create page error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Failed to load create course page: ' . $e->getMessage()], 500);
        }
    }

    private function getCourseEditContent($courseId)
    {
        try {
            $course = Course::with('category')->findOrFail($courseId);

            // Check if user owns this course or is super admin
            if (!auth()->user()->isSuperAdmin() && $course->creator_id !== auth()->id()) {
                return response()->json(['error' => 'Access denied'], 403);
            }

            $categories = Category::all();

            $html = view('admin.partials.course-edit-content', compact('course', 'categories'))->render();
            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            \Log::error('Course edit page error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load edit course page'], 500);
        }
    }

    /**
     * Get course content management page
     */
    private function getCourseContentManagement(string $courseId)
    {
        try {
            $course = Course::with([
                'chapters.lessons' => function ($query) {
                    $query->orderBy('order');
                }
            ])->findOrFail($courseId);

            $chapters = $course->chapters()->with('lessons')->orderBy('order')->get();

            $html = view('admin.partials.course-content-management', compact('course', 'chapters'))->render();
            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            \Log::error('Course content management page error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load course content management page'], 500);
        }
    }

    /**
     * Delete course (admin and superadmin)
     */
    public function deleteCourse(Course $course): JsonResponse
    {
        try {

            // Check permission - creator or super admin can delete
            if (!auth()->user()->isSuperAdmin() && $course->creator_id !== auth()->id()) {
                return response()->json(['error' => 'Access denied. You can only delete your own courses.'], 403);
            }

            // Check if course has enrollments
            $enrollmentCount = $course->enrollments()->count();
            if ($enrollmentCount > 0) {
                return response()->json([
                    'error' => "Cannot delete course '{$course->title}'. It has {$enrollmentCount} active enrollment(s). Please remove all enrollments first or contact support for force deletion."
                ], 400);
            }

            // Delete associated files
            if ($course->thumbnail) {
                \Storage::disk('public')->delete($course->thumbnail);
            }
            if ($course->main_video_url) {
                \Storage::disk('public')->delete($course->main_video_url);
            }

            $courseName = $course->title;
            $course->delete();

            return response()->json([
                'success' => true,
                'message' => "Course '{$courseName}' has been successfully deleted."
            ]);

        } catch (\Exception $e) {
            \Log::error('Course deletion error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete course: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store new course
     */
    public function storeCourse(Request $request): JsonResponse
    {
        try {
            \Log::info('StoreCourse called', [
                'user_id' => auth()->id(),
                'request_data' => $request->all(),
                'files' => $request->allFiles()
            ]);

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'level' => 'required|in:beginner,intermediate,advanced',
                'duration_hours' => 'required|integer|min:1',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,flv|max:102400', // max 100MB
                'video_title' => 'nullable|string|max:255',
                'is_published' => 'boolean'
            ]);

            $course = new Course();
            $course->title = $request->title;
            $course->slug = \Str::slug($request->title) . '-' . time(); // Generate unique slug
            $course->description = $request->description;
            $course->category_id = $request->category_id;
            $course->price = $request->price;
            $course->level = $request->level;
            $course->duration_hours = $request->duration_hours;
            $course->is_published = $request->boolean('is_published', false);
            $course->creator_id = auth()->id();

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('course-thumbnails', 'public');
                $course->thumbnail = $path;
            }

            // Handle video upload  
            if ($request->hasFile('video')) {
                // Create directory if not exists
                $uploadDir = 'courses/videos';
                if (!\Storage::disk('public')->exists($uploadDir)) {
                    \Storage::disk('public')->makeDirectory($uploadDir);
                }

                // Generate unique filename to prevent conflicts
                $file = $request->file('video');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $filename = $originalName . '_' . time() . '_' . uniqid() . '.' . $extension;

                // Store video in organized folder structure
                $relativePath = $uploadDir . '/' . $filename;
                $file->storeAs('', $relativePath, 'public');

                // Save video info to course
                $course->main_video_url = $relativePath;
                $course->video_title = $request->input('video_title') ?: $course->title;
            }

            $course->save();

            return response()->json([
                'success' => true,
                'message' => 'Course created successfully',
                'course' => $course
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Course creation error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create course'], 500);
        }
    }

    /**
     * Update course
     */
    public function updateCourse(Request $request, Course $course): JsonResponse
    {
        try {
            // Check authorization
            if (!auth()->user()->isSuperAdmin() && $course->creator_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized. You can only update your own courses.'
                ], 403);
            }

            \Log::info('Updating course', [
                'course_id' => $course->id,
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'level' => 'required|in:beginner,intermediate,advanced',
                'duration_hours' => 'nullable|integer|min:0',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_published' => 'boolean'
            ]);

            $course->title = $request->title;
            $course->description = $request->description;
            $course->category_id = $request->category_id;
            $course->price = $request->input('price', 0) ?: 0;
            $course->level = $request->level;
            $course->duration_hours = $request->input('duration_hours', 0) ?: 0;
            $course->is_published = $request->boolean('is_published', $course->is_published);

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if exists
                if ($course->thumbnail) {
                    \Storage::disk('public')->delete($course->thumbnail);
                }

                $path = $request->file('thumbnail')->store('course-thumbnails', 'public');
                $course->thumbnail = $path;
            }

            $course->save();

            \Log::info('Course updated successfully', ['course_id' => $course->id]);

            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully',
                'course' => $course
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Course update validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Course update error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to update course: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload simple video for course
     */
    public function uploadVideo(Request $request, Course $course): JsonResponse
    {
        \Log::info('Upload video method called', [
            'course_id' => $course->id,
            'request_data' => $request->all(),
            'files' => $request->allFiles()
        ]);

        try {
            $request->validate([
                'video' => 'required|file|mimes:mp4,mov,avi,wmv,flv|max:102400', // max 100MB
                'video_title' => 'nullable|string|max:255'
            ]);

            // Delete old video if exists
            if ($course->main_video_url) {
                \Storage::disk('public')->delete($course->main_video_url);
            }

            // Create directory if not exists
            $uploadDir = 'courses/videos';
            if (!\Storage::disk('public')->exists($uploadDir)) {
                \Storage::disk('public')->makeDirectory($uploadDir);
            }

            // Generate unique filename to prevent conflicts
            $file = $request->file('video');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = $originalName . '_' . time() . '_' . uniqid() . '.' . $extension;

            // Store video in organized folder structure
            $relativePath = $uploadDir . '/' . $filename;
            $file->storeAs('', $relativePath, 'public');

            // Save only the relative path in database (not full URL)
            $course->main_video_url = $relativePath;
            $course->video_title = $request->input('video_title') ?: $course->title;
            $course->save();

            \Log::info('Video uploaded successfully', [
                'course_id' => $course->id,
                'filename' => $filename,
                'path' => $relativePath,
                'size' => $file->getSize()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Video uploaded successfully',
                'video_url' => asset('storage/' . $course->main_video_url),
                'video_path' => $course->main_video_url,
                'video_title' => $course->video_title
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Video upload validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Video upload error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'course_id' => $course->id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload video',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete simple video for course
     */
    public function deleteVideo(Course $course): JsonResponse
    {
        try {
            if ($course->main_video_url) {
                // Delete the video file using the stored path
                \Storage::disk('public')->delete($course->main_video_url);

                \Log::info('Video deleted', [
                    'course_id' => $course->id,
                    'deleted_path' => $course->main_video_url
                ]);

                $course->main_video_url = null;
                $course->video_title = null;
                $course->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Video removed successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Video delete error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to remove video'], 500);
        }
    }

    /**
     * Create course from SCORM package(s)
     */
    public function createScormCourse(Request $request): JsonResponse
    {
        try {
            // Support both single and multiple files
            $files = [];
            if ($request->hasFile('scorm_packages')) {
                $files = is_array($request->file('scorm_packages')) ? $request->file('scorm_packages') : [$request->file('scorm_packages')];
            } elseif ($request->hasFile('scorm_package')) {
                $files = [$request->file('scorm_package')];
            }

            \Log::info('Create SCORM course - files count: ' . count($files));

            if (empty($files)) {
                return response()->json([
                    'success' => false,
                    'error' => 'SCORM package file(s) required'
                ], 400);
            }

            // Validate category
            if (!$request->input('category_id')) {
                return response()->json([
                    'success' => false,
                    'error' => 'Category is required'
                ], 400);
            }

            if (!Category::find($request->input('category_id'))) {
                return response()->json([
                    'success' => false,
                    'error' => 'Category not found'
                ], 400);
            }

            // Validate all files
            foreach ($files as $file) {
                if (!$file->isValid()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Invalid file: ' . $file->getClientOriginalName()
                    ], 400);
                }

                if ($file->getClientOriginalExtension() !== 'zip') {
                    return response()->json([
                        'success' => false,
                        'error' => 'All files must be ZIP'
                    ], 400);
                }

                if ($file->getSize() > 500 * 1024 * 1024) {
                    return response()->json([
                        'success' => false,
                        'error' => 'File too large: ' . $file->getClientOriginalName()
                    ], 400);
                }
            }

            // Create course
            $title = $request->input('title');
            if (empty($title)) {
                $title = pathinfo($files[0]->getClientOriginalName(), PATHINFO_FILENAME);
            }

            $description = $request->input('description');
            if (empty($description)) {
                $description = 'SCORM Multi-Chapter Course';
            }

            $course = Course::create([
                'title' => $title,
                'slug' => \Illuminate\Support\Str::slug($title) . '-' . uniqid(),
                'description' => $description,
                'price' => floatval($request->input('price')) ?? 0,
                'level' => $request->input('level', 'beginner') ?? 'beginner',
                'duration_hours' => intval($request->input('duration_hours')) ?? count($files),
                'creator_id' => auth()->id(),
                'category_id' => $request->input('category_id'),
                'content_type' => count($files) > 1 ? 'scorm_multi' : 'scorm',
                'is_published' => false,
                'total_chapters' => count($files)
            ]);

            \Log::info('Course created: ' . $course->id);

            // Process each file as a chapter
            foreach ($files as $index => $file) {
                \Log::info('Processing file ' . ($index + 1) . ': ' . $file->getClientOriginalName());
                $this->processScormFile($course, $file, $index + 1);
            }

            \Log::info('All files processed successfully');

            return response()->json([
                'success' => true,
                'message' => 'SCORM course created successfully!',
                'course' => $course,
                'redirect' => route('admin.dashboard.editcourse', $course)
            ]);

        } catch (\Exception $e) {
            \Log::error('Create SCORM course error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function processScormFile(Course $course, $file, $order = 1)
    {
        // Extract ZIP
        $zip = new ZipArchive();
        $extractPath = storage_path('app/scorm/' . uniqid('scorm_'));

        if (!is_dir(dirname($extractPath))) {
            mkdir(dirname($extractPath), 0755, true);
        }

        if ($zip->open($file->getPathname()) !== TRUE) {
            throw new \Exception('Cannot open ZIP file');
        }

        $zip->extractTo($extractPath);
        $zip->close();

        // Find manifest
        $manifestPath = $extractPath . '/imsmanifest.xml';
        if (!file_exists($manifestPath)) {
            throw new \Exception('imsmanifest.xml not found');
        }

        // Parse manifest
        $dom = new DOMDocument();
        $dom->load($manifestPath);

        // Detect SCORM version
        $scormVersion = '1.2';
        if ($dom->documentElement->hasAttribute('xmlns')) {
            $xmlns = $dom->documentElement->getAttribute('xmlns');
            if (strpos($xmlns, '2004') !== false) {
                $scormVersion = '2004';
            }
        }

        // Get title
        $titleNodes = $dom->getElementsByTagName('title');
        $chapterTitle = $titleNodes->length > 0 ? $titleNodes->item(0)->textContent : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Get entry point
        $entryPoint = 'index.html';
        $resourceNodes = $dom->getElementsByTagName('resource');
        if ($resourceNodes->length > 0) {
            $href = $resourceNodes->item(0)->getAttribute('href');
            if ($href) {
                $entryPoint = $href;
            }
        }

        // Create CourseScormChapter entry
        $filename = $file->getClientOriginalName();

        if ($course->content_type === 'scorm_multi') {
            // Multi-chapter - always create chapter entry
            $course->scormChapters()->create([
                'title' => $chapterTitle,
                'filename' => $filename,
                'description' => 'Chapter ' . $order,
                'order' => $order,
                'scorm_version' => $scormVersion,
                'scorm_manifest' => json_encode(['path' => $manifestPath]),
                'scorm_entry_point' => $entryPoint,
                'scorm_package_path' => basename($extractPath),
                'duration_minutes' => 0,
                'is_published' => true
            ]);
        } else {
            // Single chapter - store in course directly
            $course->update([
                'scorm_version' => $scormVersion,
                'scorm_entry_point' => $entryPoint,
                'scorm_package_path' => basename($extractPath),
                'scorm_manifest' => json_encode(['path' => $manifestPath])
            ]);
        }
    }

    private function processSimpleScorm($file, $request)
    {
        // Extract ZIP file
        $zip = new \ZipArchive();
        $extractPath = storage_path('app/scorm/' . uniqid('scorm_'));

        if (!file_exists(dirname($extractPath))) {
            mkdir(dirname($extractPath), 0755, true);
        }

        if ($zip->open($file->getPathname()) !== TRUE) {
            throw new \Exception('Cannot open SCORM ZIP file');
        }

        $zip->extractTo($extractPath);
        $zip->close();

        // Look for imsmanifest.xml
        $manifestPath = $extractPath . '/imsmanifest.xml';
        if (!file_exists($manifestPath)) {
            throw new \Exception('imsmanifest.xml not found in SCORM package');
        }

        // Parse manifest for basic info
        $dom = new \DOMDocument();
        $dom->load($manifestPath);

        // Detect SCORM version
        $scormVersion = null;
        if ($dom->documentElement->hasAttribute('xmlns')) {
            $xmlns = $dom->documentElement->getAttribute('xmlns');
            if (strpos($xmlns, '2004') !== false) {
                $scormVersion = '2004';
            } elseif (strpos($xmlns, '1.2') !== false) {
                $scormVersion = '1.2';
            }
        }

        // Get course title
        $titleNodes = $dom->getElementsByTagName('title');
        $courseTitle = $titleNodes->length > 0 ? $titleNodes->item(0)->textContent : 'SCORM Course';

        // Get entry point if available
        $entryPoint = null;
        $resourceNodes = $dom->getElementsByTagName('resource');
        if ($resourceNodes->length > 0) {
            $entryPoint = $resourceNodes->item(0)->getAttribute('href');
        }

        // Create course record
        $title = $request->input('title');
        if (empty($title) || !$title) {
            $title = $courseTitle;
        }

        $description = $request->input('description');
        if (empty($description)) {
            $description = 'SCORM Course';
        }

        $course = Course::create([
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title) . '-' . uniqid(),
            'description' => $description,
            'price' => $request->input('price', 0),
            'level' => $request->input('level', 'beginner'),
            'duration_hours' => $request->input('duration_hours', 1),
            'creator_id' => auth()->id(),
            'category_id' => $request->input('category_id'),
            'content_type' => 'scorm',
            'scorm_package_path' => basename($extractPath), // Just folder name, no prefix
            'scorm_version' => $scormVersion,
            'scorm_entry_point' => $entryPoint,
            'scorm_manifest' => $manifestPath,
            'is_published' => false
        ]);

        \Log::info('SCORM course created', [
            'course_id' => $course->id,
            'title' => $course->title,
            'path' => $course->scorm_package_path,
            'entry_point' => $entryPoint,
            'version' => $scormVersion
        ]);

        return $course;
    }

    /**
     * Delete user (Super Admin only)
     */


    /**
     * Make user admin (Super Admin only)
     */
    public function makeUserAdmin(Request $request, string $userId): JsonResponse
    {
        try {
            // Only super admin can promote users
            if (!auth()->user()->isSuperAdmin()) {
                return response()->json(['error' => 'Access denied. Only super admin can promote users.'], 403);
            }

            $user = \App\Models\User::findOrFail($userId);

            if ($user->role === 'admin') {
                return response()->json(['error' => 'User is already an admin.'], 400);
            }

            if ($user->role === 'super_admin') {
                return response()->json(['error' => 'Cannot modify super admin role.'], 400);
            }

            $user->role = 'admin';
            $user->save();

            return response()->json([
                'success' => true,
                'message' => "User '{$user->name}' has been promoted to admin successfully."
            ]);

        } catch (\Exception $e) {
            \Log::error('Make admin error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to promote user: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Create new user (super admin only)
     */
    public function createUser(Request $request): JsonResponse
    {
        try {
            if (!auth()->user()->isSuperAdmin()) {
                return response()->json(['error' => 'Access denied'], 403);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:user,admin,super_admin'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'email_verified_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => "User '{$user->name}' created successfully."
            ]);

        } catch (\Exception $e) {
            \Log::error('Create user error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create user: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update user (super admin only)
     */
    public function updateUser(Request $request, User $user): JsonResponse
    {
        try {
            if (!auth()->user()->isSuperAdmin()) {
                return response()->json(['error' => 'Access denied'], 403);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'role' => 'required|in:user,admin,super_admin'
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role
            ]);

            return response()->json([
                'success' => true,
                'message' => "User '{$user->name}' updated successfully."
            ]);

        } catch (\Exception $e) {
            \Log::error('Update user error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update user: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete user (super admin only)
     */
    public function deleteUser(User $user): JsonResponse
    {
        try {
            if (!auth()->user()->isSuperAdmin()) {
                return response()->json(['error' => 'Access denied'], 403);
            }

            // Prevent deleting self
            if ($user->id === auth()->id()) {
                return response()->json(['error' => 'Cannot delete your own account'], 400);
            }

            // Check if user has enrollments
            $enrollmentCount = $user->enrollments()->count();
            if ($enrollmentCount > 0) {
                return response()->json([
                    'error' => "Cannot delete user '{$user->name}'. User has {$enrollmentCount} active enrollment(s)."
                ], 400);
            }

            $userName = $user->name;
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => "User '{$userName}' deleted successfully."
            ]);

        } catch (\Exception $e) {
            \Log::error('Delete user error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete user: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Reorder SCORM chapters
     */
    public function reorderChapters(Request $request, Course $course): JsonResponse
    {
        try {
            // Verify course is scorm_multi
            if ($course->content_type !== 'scorm_multi') {
                return response()->json([
                    'success' => false,
                    'error' => 'This course does not support chapter reordering'
                ], 400);
            }

            $chapters = $request->input('chapters', []);

            if (empty($chapters)) {
                return response()->json([
                    'success' => false,
                    'error' => 'No chapters provided'
                ], 400);
            }

            \Log::info('Reordering chapters for course ' . $course->id, ['chapters' => $chapters]);

            // Update order for each chapter
            foreach ($chapters as $chapter) {
                $chapterId = $chapter['id'] ?? null;
                $order = $chapter['order'] ?? null;

                if (!$chapterId || !$order) {
                    continue;
                }

                CourseScormChapter::where('id', $chapterId)
                    ->where('course_id', $course->id)
                    ->update(['order' => $order]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Chapter order updated successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Reorder chapters error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to reorder chapters: ' . $e->getMessage()
            ], 500);
        }
    }

}
