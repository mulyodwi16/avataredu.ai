<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardApiController extends Controller
{
    protected UserService $userService;
    protected CourseService $courseService;

    public function __construct(UserService $userService, CourseService $courseService)
    {
        $this->userService = $userService;
        $this->courseService = $courseService;
    }

    /**
     * Get page content for single page navigation
     */
    public function getContent(Request $request, string $page): JsonResponse
    {
        $user = auth()->user();

        try {
            switch ($page) {
                case 'dashboard':
                    return $this->getDashboardContent($user);

                case 'account':
                    return $this->getAccountContent($user);

                case 'courses':
                    return $this->getCoursesContent($request);

                case 'collection':
                    return $this->getCollectionContent($user);

                case 'purchase-history':
                    return $this->getPurchaseHistoryContent($user);

                default:
                    return response()->json(['error' => 'Page not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error loading content'], 500);
        }
    }

    private function getDashboardContent($user)
    {
        $stats = $this->userService->getDashboardStats($user);
        $enrolledCourses = $this->userService->getUserCourses($user, ['per_page' => 6]);
        $recentCourses = $this->userService->getUserCourses($user, ['per_page' => 3]);
        $recommendations = $this->userService->getRecommendations($user, 6);

        $html = view('partials.dashboard-content', compact(
            'user',
            'stats',
            'enrolledCourses',
            'recentCourses',
            'recommendations'
        ))->render();

        return response()->json(['html' => $html]);
    }

    private function getAccountContent($user)
    {
        $html = view('partials.account-content', compact('user'))->render();
        return response()->json(['html' => $html]);
    }

    private function getCoursesContent(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $sort = $request->get('sort', 'latest');

        // Get courses using simple query builder for now
        $query = \App\Models\Course::where('is_published', true);

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($category) {
            $query->where('category_id', $category);
        }

        switch ($sort) {
            case 'popular':
                $query->orderBy('students_count', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $courses = $query->with('category')->paginate(12);
        $categories = \App\Models\Category::all();

        $html = view('partials.courses-content', compact('courses', 'categories', 'search', 'category', 'sort'))->render();
        return response()->json(['html' => $html]);
    }

    private function getCollectionContent($user)
    {
        $enrolledCourses = $this->userService->getUserCourses($user);

        $html = view('partials.collection-content', compact('user', 'enrolledCourses'))->render();
        return response()->json(['html' => $html]);
    }

    private function getPurchaseHistoryContent($user)
    {
        // Get user's transactions with course items
        $transactions = $user->transactions()->with(['items.course.category'])->latest()->get();

        $html = view('partials.purchase-history-content', compact('user', 'transactions'))->render();
        return response()->json(['html' => $html]);
    }
}