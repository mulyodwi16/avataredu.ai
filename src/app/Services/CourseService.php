<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseService
{
    /**
     * Get paginated published courses with filters
     */
    public function getPublishedCourses(array $filters = []): LengthAwarePaginator
    {
        $query = Course::published()
            ->withAuthor()
            ->with(['category:id,name'])
            ->latest('published_at');

        // Apply filters
        if (!empty($filters['category'])) {
            $query->byCategory($filters['category']);
        }

        if (!empty($filters['level'])) {
            $query->byLevel($filters['level']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['price_range'])) {
            switch ($filters['price_range']) {
                case 'free':
                    $query->where('price', 0);
                    break;
                case 'under_100k':
                    $query->where('price', '>', 0)->where('price', '<', 100000);
                    break;
                case 'over_100k':
                    $query->where('price', '>=', 100000);
                    break;
            }
        }

        return $query->paginate($filters['per_page'] ?? 12);
    }

    /**
     * Get featured courses for homepage
     */
    public function getFeaturedCourses(int $limit = 6): Collection
    {
        return Course::published()
            ->withAuthor()
            ->with(['category:id,name'])
            ->where('average_rating', '>=', 4.0)
            ->orWhere('enrolled_count', '>=', 100)
            ->orderBy('enrolled_count', 'desc')
            ->orderBy('average_rating', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get courses by admin
     */
    public function getCoursesByAdmin(int $adminId, array $filters = []): LengthAwarePaginator
    {
        $query = Course::where('creator_id', $adminId)
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
     * Create a new course
     */
    public function createCourse(array $data, ?UploadedFile $thumbnail = null, ?UploadedFile $video = null): Course
    {
        if ($thumbnail) {
            $data['thumbnail'] = $this->uploadThumbnail($thumbnail);
        }

        if ($video) {
            $data['video_path'] = $this->uploadVideo($video);
        }

        $data['slug'] = $this->generateUniqueSlug($data['title']);
        $data['is_published'] = $data['is_published'] ?? false;

        if ($data['is_published']) {
            $data['published_at'] = now();
        }

        return Course::create($data);
    }

    /**
     * Update an existing course
     */
    public function updateCourse(Course $course, array $data, ?UploadedFile $thumbnail = null, ?UploadedFile $video = null): Course
    {
        if ($thumbnail) {
            // Delete old thumbnail if exists
            if ($course->thumbnail) {
                $this->deleteThumbnail($course->thumbnail);
            }
            $data['thumbnail'] = $this->uploadThumbnail($thumbnail);
        }

        if ($video) {
            // Delete old video if exists
            if ($course->video_path) {
                $this->deleteVideo($course->video_path);
            }
            $data['video_path'] = $this->uploadVideo($video);
        }

        // Update slug if title changed
        if (isset($data['title']) && $data['title'] !== $course->title) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $course->id);
        }

        // Set published_at if publishing for first time
        if (isset($data['is_published']) && $data['is_published'] && !$course->published_at) {
            $data['published_at'] = now();
        }

        $course->update($data);
        return $course->refresh();
    }

    /**
     * Delete a course and its associated files
     */
    public function deleteCourse(Course $course): bool
    {
        if ($course->thumbnail) {
            $this->deleteThumbnail($course->thumbnail);
        }

        return $course->delete();
    }

    /**
     * Get course statistics for admin dashboard
     */
    public function getCourseStatistics(int $adminId): array
    {
        $courses = Course::where('creator_id', $adminId);

        return [
            'total_courses' => $courses->count(),
            'published_courses' => $courses->where('is_published', true)->count(),
            'draft_courses' => $courses->where('is_published', false)->count(),
            'total_students' => $courses->sum('enrolled_count'),
            'total_revenue' => $courses->where('is_published', true)->sum('price'),
            'average_rating' => $courses->where('is_published', true)->avg('average_rating'),
        ];
    }

    /**
     * Search courses with advanced filters
     */
    public function searchCourses(string $query, array $filters = []): LengthAwarePaginator
    {
        $courseQuery = Course::published()
            ->withAuthor()
            ->with(['category:id,name'])
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%')
                    ->orWhereHas('category', function ($catQuery) use ($query) {
                        $catQuery->where('name', 'like', '%' . $query . '%');
                    })
                    ->orWhereHas('creator', function ($creatorQuery) use ($query) {
                        $creatorQuery->where('name', 'like', '%' . $query . '%');
                    });
            });

        // Apply additional filters
        if (!empty($filters['category'])) {
            $courseQuery->byCategory($filters['category']);
        }

        if (!empty($filters['level'])) {
            $courseQuery->byLevel($filters['level']);
        }

        return $courseQuery->paginate($filters['per_page'] ?? 12);
    }

    /**
     * Get all categories for filter dropdowns
     */
    public function getAllCategories(): Collection
    {
        return Category::withCount('courses')->orderBy('name')->get();
    }

    /**
     * Get courses created by a specific user (admin)
     */
    public function getCreatedCourses($user, array $filters = []): LengthAwarePaginator
    {
        $query = Course::where('creator_id', $user->id)
            ->with(['category:id,name'])
            ->latest();

        $perPage = $filters['per_page'] ?? 15;
        return $query->paginate($perPage);
    }

    /**
     * Get total courses count for admin stats
     */
    public function getTotalCoursesCount(): int
    {
        return Course::count();
    }

    /**
     * Upload and store course thumbnail
     */
    protected function uploadThumbnail(UploadedFile $file): string
    {
        return $file->store('courses', 'public');
    }

    /**
     * Delete course thumbnail from storage
     */
    protected function deleteThumbnail(string $path): void
    {
        Storage::disk('public')->delete($path);
    }

    /**
     * Generate unique slug for course
     */
    protected function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        $query = Course::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;

            $query = Course::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    /**
     * Upload and store course video
     */
    protected function uploadVideo(UploadedFile $file): string
    {
        return $file->store('courses/videos', 'public');
    }

    /**
     * Delete course video from storage
     */
    protected function deleteVideo(string $path): void
    {
        Storage::disk('public')->delete($path);
    }

    /**
     * Get course detail with all related information
     */
    public function getCourseDetail(int $courseId): ?Course
    {
        return Course::with([
            'category:id,name',
            'creator:id,name,email',
            'chapters.lessons' => function ($query) {
                $query->orderBy('order_index');
            }
        ])
            ->where('id', $courseId)
            ->where('is_published', true)
            ->first();
    }
}