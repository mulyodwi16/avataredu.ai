<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    protected UserService $userService;
    protected CourseService $courseService;

    public function __construct(UserService $userService, CourseService $courseService)
    {
        $this->userService = $userService;
        $this->courseService = $courseService;
    }

    /**
     * Display user dashboard
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = auth()->user();

        // Redirect admins to admin dashboard
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Get dashboard statistics for regular users
        $stats = $this->userService->getDashboardStats($user);

        // User dashboard - show enrolled courses and recommendations
        $enrolledCourses = $this->userService->getUserCourses($user, ['per_page' => 6]);
        $recentCourses = $this->userService->getUserCourses($user, ['per_page' => 3]);
        $recommendations = $this->userService->getRecommendations($user, 6);



        return view('dashboard', compact(
            'user',
            'stats',
            'enrolledCourses',
            'recentCourses',
            'recommendations'
        ));
    }

    /**
     * Get course detail for dashboard API
     */
    public function getCourseDetail($courseId)
    {
        try {
            $course = $this->courseService->getCourseDetail($courseId);

            if (!$course) {
                return response()->json(['error' => 'Course not found'], 404);
            }

            // Return the course detail view as HTML
            $html = view('partials.course-detail-content', compact('course'))->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'course' => $course->only(['id', 'title', 'price'])
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching course detail: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching course details'], 500);
        }
    }

    /**
     * Get user's enrolled courses (My Collection)
     */
    public function getMyCollection()
    {
        try {
            $user = auth()->user();
            $enrolledCourses = $this->userService->getUserCourses($user, ['per_page' => 12]);

            $html = view('partials.collection-content', compact('enrolledCourses', 'user'))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching user collection: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching your courses'], 500);
        }
    }

    /**
     * Get user's purchase history
     */
    public function getPurchaseHistory()
    {
        try {
            $user = auth()->user();
            $transactions = $user->transactions()->with(['items.course'])->latest()->get();

            $html = view('partials.purchase-history-content', compact('transactions', 'user'))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching purchase history: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching purchase history'], 500);
        }
    }
}