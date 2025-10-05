<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Services\AdminService;
use App\Services\CourseService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $adminService;
    protected $courseService;

    public function __construct(AdminService $adminService, CourseService $courseService)
    {
        $this->adminService = $adminService;
        $this->courseService = $courseService;
    }

    public function index()
    {
        try {
            // Get admin statistics
            $stats = $this->adminService->getDashboardStats();

            // Get recent courses created by this admin
            $recentCourses = Course::where('creator_id', auth()->id())
                ->latest()
                ->take(6)
                ->get();

            // Get featured/popular courses for overview - simplified
            $featuredCourses = Course::where('is_published', true)
                ->latest()
                ->take(6)
                ->get();

            // Get total courses count across platform (for super admin)
            $allCoursesCount = auth()->user()->isSuperAdmin()
                ? Course::count()
                : Course::where('creator_id', auth()->id())->count();

            return view('admin.dashboard', compact(
                'stats',
                'recentCourses',
                'featuredCourses',
                'allCoursesCount'
            ));
        } catch (\Exception $e) {
            // Log error and return simple view
            \Log::error('Admin dashboard error: ' . $e->getMessage());

            return view('admin.dashboard', [
                'stats' => [
                    'total_courses' => 0,
                    'published_courses' => 0,
                    'draft_courses' => 0,
                    'total_students' => 0,
                    'total_enrollments' => 0,
                ],
                'recentCourses' => collect([]),
                'featuredCourses' => collect([]),
                'allCoursesCount' => 0,
            ]);
        }
    }
}