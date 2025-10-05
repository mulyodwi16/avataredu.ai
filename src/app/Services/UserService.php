<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Institution;
use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    /**
     * Get user's enrolled courses with progress
     */
    public function getUserCourses(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = $user->enrolledCourses()
            ->with(['category:id,name', 'creator:id,name,avatar'])
            ->withPivot('progress_percentage', 'completed_at', 'last_accessed_at', 'created_at', 'updated_at');

        if (!empty($filters['status'])) {
            switch ($filters['status']) {
                case 'completed':
                    $query->whereNotNull('enrollments.completed_at');
                    break;
                case 'in_progress':
                    $query->whereNull('enrollments.completed_at')
                        ->where('enrollments.progress_percentage', '>', 0);
                    break;
                case 'not_started':
                    $query->where('enrollments.progress_percentage', 0);
                    break;
            }
        }

        return $query->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Get user's created courses (for creators)
     */
    public function getCreatedCourses(User $user, array $filters = []): LengthAwarePaginator
    {
        if (!$user->isAdmin()) {
            return collect()->paginate();
        }

        $query = $user->createdCourses()
            ->withCount('enrollments as students_count')
            ->with(['category:id,name'])
            ->latest();

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'published') {
                $query->where('is_published', true);
            } elseif ($filters['status'] === 'draft') {
                $query->where('is_published', false);
            }
        }

        return $query->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Get user dashboard statistics
     */
    public function getDashboardStats(User $user): array
    {
        if ($user->isAdmin()) {
            return $this->getCreatorStats($user);
        }

        return $this->getStudentStats($user);
    }

    /**
     * Get student dashboard statistics
     */
    protected function getStudentStats(User $user): array
    {
        // Get enrollment counts from pivot table
        $totalEnrollments = $user->enrolledCourses()->count();
        $completedCourses = $user->enrolledCourses()->whereNotNull('enrollments.completed_at')->count();
        $inProgressCourses = $user->enrolledCourses()
            ->whereNull('enrollments.completed_at')
            ->where('enrollments.progress_percentage', '>', 0)
            ->count();

        return [
            'enrolled_courses' => $totalEnrollments,
            'completed_courses' => $completedCourses,
            'in_progress_courses' => $inProgressCourses,
            'certificates_earned' => $completedCourses,
            'learning_hours' => $this->calculateLearningHours($user),
            'current_streak' => $this->calculateLearningStreak($user),
        ];
    }

    /**
     * Get creator dashboard statistics
     */
    protected function getCreatorStats(User $user): array
    {
        $courses = $user->createdCourses();

        return [
            'total_courses' => $courses->count(),
            'published_courses' => $courses->where('is_published', true)->count(),
            'draft_courses' => $courses->where('is_published', false)->count(),
            'total_students' => User::where('role', 'user')->count(), // Count regular users, not enrollments
            'total_enrollments' => $courses->sum('enrolled_count'), // Track actual enrollments separately
            'total_revenue' => $this->calculateRevenue($user),
            'average_rating' => $courses->where('is_published', true)->avg('average_rating') ?? 0,
            'monthly_earnings' => $this->calculateMonthlyEarnings($user),
        ];
    }

    /**
     * Get user's institution learning data
     */
    public function getInstitutionData(User $user): array
    {
        $institutions = $user->institutions()->with(['departments', 'courses'])->get();

        $data = [];
        foreach ($institutions as $institution) {
            $userPivot = $institution->pivot;
            $availableCourses = $institution->courses()
                ->where(function ($query) use ($userPivot) {
                    $query->whereNull('institution_courses.department_id')
                        ->orWhere('institution_courses.department_id', $userPivot->department_id);
                })
                ->with(['category:id,name', 'creator:id,name'])
                ->get();

            $data[] = [
                'institution' => $institution,
                'user_data' => [
                    'student_id' => $userPivot->student_id,
                    'department' => Department::find($userPivot->department_id),
                    'status' => $userPivot->status,
                    'metadata' => $userPivot->metadata,
                ],
                'available_courses' => $availableCourses,
                'enrolled_courses' => $user->enrolledCourses()
                    ->whereIn('courses.id', $availableCourses->pluck('id'))
                    ->get(),
            ];
        }

        return $data;
    }

    /**
     * Update user profile information
     */
    public function updateProfile(User $user, array $data): User
    {
        // Filter only updatable fields
        $allowedFields = [
            'name',
            'bio',
            'expertise',
            'website',
            'social_links'
        ];

        $updateData = array_intersect_key($data, array_flip($allowedFields));

        $user->update($updateData);
        return $user->refresh();
    }

    /**
     * Get user's learning recommendations based on enrolled courses and preferences
     */
    public function getRecommendations(User $user, int $limit = 6): Collection
    {
        // Get categories from user's enrolled courses
        $enrolledCategoryIds = $user->enrolledCourses()->pluck('category_id')->unique();

        // Get courses from same categories but not enrolled
        $recommendations = Course::published()
            ->whereIn('category_id', $enrolledCategoryIds)
            ->whereNotIn('id', $user->enrolledCourses()->pluck('courses.id'))
            ->with(['category:id,name', 'creator:id,name,avatar'])
            ->orderBy('average_rating', 'desc')
            ->orderBy('enrolled_count', 'desc')
            ->limit($limit)
            ->get();

        // If not enough recommendations, fill with popular courses
        if ($recommendations->count() < $limit) {
            $additionalCourses = Course::published()
                ->whereNotIn('id', $user->enrolledCourses()->pluck('courses.id'))
                ->whereNotIn('id', $recommendations->pluck('id'))
                ->with(['category:id,name', 'creator:id,name,avatar'])
                ->orderBy('enrolled_count', 'desc')
                ->limit($limit - $recommendations->count())
                ->get();

            $recommendations = $recommendations->merge($additionalCourses);
        }

        return $recommendations;
    }

    /**
     * Calculate total learning hours for user
     */
    protected function calculateLearningHours(User $user): float
    {
        // This would typically be calculated based on actual learning sessions
        // For now, we'll estimate based on course durations and progress
        return $user->enrolledCourses()
            ->get()
            ->sum(function ($course) {
                $progress = $course->pivot->progress_percentage ?? 0;
                $duration = is_numeric($course->duration) ? $course->duration : 0;
                return ($progress / 100) * $duration;
            });
    }

    /**
     * Calculate learning streak
     */
    protected function calculateLearningStreak(User $user): int
    {
        // This would be based on actual learning session logs
        // Placeholder implementation
        return rand(1, 15);
    }

    /**
     * Calculate total revenue for creator
     */
    protected function calculateRevenue(User $user): float
    {
        // This would be based on actual transaction records
        // For now, estimate based on enrolled students and course prices
        return $user->createdCourses()
            ->get()
            ->sum(function ($course) {
                return $course->enrolled_count * $course->price;
            });
    }

    /**
     * Calculate monthly earnings for creator
     */
    protected function calculateMonthlyEarnings(User $user): float
    {
        // This would be based on transaction records from current month
        // Placeholder implementation
        return $this->calculateRevenue($user) * 0.1; // Assume 10% of total revenue per month
    }
}
