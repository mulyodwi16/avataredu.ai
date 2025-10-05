<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminService
{
    /**
     * Get dashboard statistics for admin
     */
    public function getDashboardStats()
    {
        try {
            $adminId = auth()->id();
            $user = auth()->user();
            $isSuperAdmin = $user ? $user->isSuperAdmin() : false;

            if ($isSuperAdmin) {
                // Super admin sees all platform stats
                $stats = [
                    'total_courses' => Course::count(),
                    'published_courses' => Course::where('is_published', true)->count(),
                    'draft_courses' => Course::where('is_published', false)->count(),
                    'total_students' => User::where('role', 'user')->count(),
                    'total_enrollments' => DB::table('enrollments')->count(),
                    'total_admins' => User::whereIn('role', ['admin', 'super_admin'])->count(),
                ];
            } else {
                // Regular admin sees only their courses stats
                $totalCourses = Course::where('creator_id', $adminId)->count();
                $publishedCourses = Course::where('creator_id', $adminId)->where('is_published', true)->count();
                $draftCourses = Course::where('creator_id', $adminId)->where('is_published', false)->count();

                $stats = [
                    'total_courses' => $totalCourses,
                    'published_courses' => $publishedCourses,
                    'draft_courses' => $draftCourses,
                    'total_students' => 0, // Simplified for now
                    'total_enrollments' => 0, // Simplified for now
                ];
            }

            return $stats;
        } catch (\Exception $e) {
            // Return default stats if error
            return [
                'total_courses' => 0,
                'published_courses' => 0,
                'draft_courses' => 0,
                'total_students' => 0,
                'total_enrollments' => 0,
                'total_admins' => 0,
            ];
        }
    }

    /**
     * Get recent activity for admin dashboard
     */
    public function getRecentActivity()
    {
        $adminId = auth()->id();

        // Get recent enrollments for admin's courses
        $recentEnrollments = DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->join('users', 'enrollments.user_id', '=', 'users.id')
            ->where('courses.creator_id', $adminId)
            ->select(
                'enrollments.*',
                'courses.title as course_title',
                'users.name as user_name'
            )
            ->orderBy('enrollments.created_at', 'desc')
            ->take(10)
            ->get();

        return $recentEnrollments;
    }
}