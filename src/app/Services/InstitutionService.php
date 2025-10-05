<?php

namespace App\Services;

use App\Models\Institution;
use App\Models\Department;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InstitutionService
{
    /**
     * Get all active institutions
     */
    public function getActiveInstitutions(): Collection
    {
        return Institution::where('is_active', true)
            ->withCount(['students', 'departments', 'courses'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get institution with its departments and available courses
     */
    public function getInstitutionDetails(Institution $institution): array
    {
        $institution->load([
            'departments',
            'courses.category',
            'courses.creator:id,name,avatar',
            'students' => function ($query) {
                $query->limit(10); // Get sample students
            }
        ]);

        return [
            'institution' => $institution,
            'departments' => $institution->departments,
            'featured_courses' => $institution->courses()
                ->published()
                ->orderBy('students_count', 'desc')
                ->limit(6)
                ->get(),
            'total_students' => $institution->students()->count(),
            'total_courses' => $institution->courses()->count(),
        ];
    }

    /**
     * Get courses available for a specific department
     */
    public function getDepartmentCourses(Department $department, array $filters = []): LengthAwarePaginator
    {
        $query = $department->institution->courses()
            ->where(function ($q) use ($department) {
                $q->whereNull('institution_courses.department_id')
                    ->orWhere('institution_courses.department_id', $department->id);
            })
            ->with(['category:id,name', 'creator:id,name,avatar'])
            ->orderBy('institution_courses.is_mandatory', 'desc')
            ->orderBy('courses.title');

        if (!empty($filters['mandatory'])) {
            if ($filters['mandatory'] === 'yes') {
                $query->where('institution_courses.is_mandatory', true);
            } elseif ($filters['mandatory'] === 'no') {
                $query->where('institution_courses.is_mandatory', false);
            }
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('courses.title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('courses.description', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->paginate($filters['per_page'] ?? 12);
    }

    /**
     * Enroll student in institution
     */
    public function enrollStudent(User $student, Institution $institution, array $data): bool
    {
        // Check if student is already enrolled
        if ($student->institutions()->where('institution_id', $institution->id)->exists()) {
            return false;
        }

        $student->institutions()->attach($institution->id, [
            'department_id' => $data['department_id'] ?? null,
            'student_id' => $data['student_id'] ?? null,
            'status' => 'active',
            'metadata' => $data['metadata'] ?? null,
        ]);

        return true;
    }

    /**
     * Update student enrollment information
     */
    public function updateStudentEnrollment(User $student, Institution $institution, array $data): bool
    {
        $enrollment = $student->institutions()->where('institution_id', $institution->id)->first();

        if (!$enrollment) {
            return false;
        }

        $student->institutions()->updateExistingPivot($institution->id, [
            'department_id' => $data['department_id'] ?? $enrollment->pivot->department_id,
            'student_id' => $data['student_id'] ?? $enrollment->pivot->student_id,
            'status' => $data['status'] ?? $enrollment->pivot->status,
            'metadata' => $data['metadata'] ?? $enrollment->pivot->metadata,
        ]);

        return true;
    }

    /**
     * Add course to institution
     */
    public function addCourseToInstitution(Institution $institution, Course $course, array $data): bool
    {
        // Check if course is already added
        if ($institution->courses()->where('course_id', $course->id)->exists()) {
            return false;
        }

        $institution->courses()->attach($course->id, [
            'department_id' => $data['department_id'] ?? null,
            'is_mandatory' => $data['is_mandatory'] ?? false,
            'available_from' => $data['available_from'] ?? now(),
            'available_until' => $data['available_until'] ?? null,
        ]);

        return true;
    }

    /**
     * Remove course from institution
     */
    public function removeCourseFromInstitution(Institution $institution, Course $course): bool
    {
        return $institution->courses()->detach($course->id) > 0;
    }

    /**
     * Get institution statistics
     */
    public function getInstitutionStatistics(Institution $institution): array
    {
        return [
            'total_students' => $institution->students()->count(),
            'active_students' => $institution->students()
                ->wherePivot('status', 'active')->count(),
            'total_departments' => $institution->departments()->count(),
            'total_courses' => $institution->courses()->count(),
            'mandatory_courses' => $institution->courses()
                ->wherePivot('is_mandatory', true)->count(),
            'completion_rate' => $this->calculateCompletionRate($institution),
            'top_departments' => $this->getTopDepartments($institution),
        ];
    }

    /**
     * Search institutions
     */
    public function searchInstitutions(string $query, array $filters = []): LengthAwarePaginator
    {
        $institutionQuery = Institution::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('city', 'like', '%' . $query . '%')
                    ->orWhere('country', 'like', '%' . $query . '%');
            });

        if (!empty($filters['type'])) {
            $institutionQuery->where('type', $filters['type']);
        }

        if (!empty($filters['country'])) {
            $institutionQuery->where('country', $filters['country']);
        }

        return $institutionQuery->withCount(['students', 'courses'])
            ->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Get student's progress in institution courses
     */
    public function getStudentProgress(User $student, Institution $institution): array
    {
        $enrollment = $student->institutions()->where('institution_id', $institution->id)->first();

        if (!$enrollment) {
            return [];
        }

        $availableCourses = $institution->courses()
            ->where(function ($query) use ($enrollment) {
                $query->whereNull('institution_courses.department_id')
                    ->orWhere('institution_courses.department_id', $enrollment->pivot->department_id);
            })
            ->with(['category:id,name'])
            ->get();

        $enrolledCourses = $student->enrolledCourses()
            ->whereIn('courses.id', $availableCourses->pluck('id'))
            ->get();

        return [
            'total_courses' => $availableCourses->count(),
            'enrolled_courses' => $enrolledCourses->count(),
            'completed_courses' => $enrolledCourses->whereNotNull('pivot.completed_at')->count(),
            'average_progress' => $enrolledCourses->avg('pivot.progress_percentage') ?? 0,
            'courses' => $availableCourses->map(function ($course) use ($enrolledCourses) {
                $enrollment = $enrolledCourses->firstWhere('id', $course->id);
                return [
                    'course' => $course,
                    'is_enrolled' => $enrollment !== null,
                    'progress' => $enrollment ? $enrollment->pivot->progress_percentage : 0,
                    'completed' => $enrollment ? $enrollment->pivot->completed_at !== null : false,
                ];
            }),
        ];
    }

    /**
     * Calculate overall completion rate for institution
     */
    protected function calculateCompletionRate(Institution $institution): float
    {
        $totalEnrollments = $institution->students()
            ->join('enrollments', 'users.id', '=', 'enrollments.user_id')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->join('institution_courses', 'courses.id', '=', 'institution_courses.course_id')
            ->where('institution_courses.institution_id', $institution->id)
            ->count();

        if ($totalEnrollments === 0) {
            return 0;
        }

        $completedEnrollments = $institution->students()
            ->join('enrollments', 'users.id', '=', 'enrollments.user_id')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->join('institution_courses', 'courses.id', '=', 'institution_courses.course_id')
            ->where('institution_courses.institution_id', $institution->id)
            ->whereNotNull('enrollments.completed_at')
            ->count();

        return ($completedEnrollments / $totalEnrollments) * 100;
    }

    /**
     * Get top departments by student count
     */
    protected function getTopDepartments(Institution $institution): Collection
    {
        return $institution->departments()
            ->withCount(['students'])
            ->orderBy('students_count', 'desc')
            ->limit(5)
            ->get();
    }
}
