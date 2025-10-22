<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'creator_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'video_path',
        'main_video_url',
        'video_title',
        'price',
        'is_published',
        'level',
        'content_type',
        'scorm_version',
        'scorm_manifest',
        'scorm_entry_point',
        'scorm_package_path',
        'duration_hours',
        'curriculum',
        'total_chapters',
        'total_lessons',
        'enrolled_count',
        'average_rating',
        'published_at'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'curriculum' => 'json',
        'scorm_manifest' => 'json',
        'published_at' => 'datetime',
        'average_rating' => 'decimal:2'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the admin who created this course
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Alias for creator - get the admin who created this course
     */
    public function admin(): BelongsTo
    {
        return $this->creator();
    }

    /**
     * Get the students enrolled in this course
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot(['progress_percentage', 'completed_at', 'last_accessed_at'])
            ->withTimestamps();
    }

    /**
     * Get the enrollments for this course
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the chapters for this course
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(CourseChapter::class)->orderBy('order');
    }

    /**
     * Get the SCORM chapters for this course
     */
    public function scormChapters(): HasMany
    {
        return $this->hasMany(CourseScormChapter::class)->orderBy('order');
    }

    public function institutions(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'institution_courses')
            ->withPivot(['department_id', 'is_mandatory', 'available_from', 'available_until'])
            ->withTimestamps();
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'institution_courses')
            ->withPivot(['is_mandatory', 'available_from', 'available_until'])
            ->withTimestamps();
    }

    // Scope methods for better query handling
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeWithAuthor($query)
    {
        return $query->with(['creator:id,name,avatar,bio']);
    }

    // Helper methods
    public function isPublished(): bool
    {
        return $this->is_published === true;
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return asset('images/course-placeholder.jpg');
    }

    public function getVideoUrlAttribute(): ?string
    {
        if ($this->video_path) {
            return asset('storage/' . $this->video_path);
        }
        return null;
    }

    public function getDurationTextAttribute(): string
    {
        if (empty($this->duration_hours) || $this->duration_hours == 0) {
            return 'Not specified';
        }

        return $this->duration_hours . ' hours';
    }

    /**
     * Get progress percentage for current enrollment (when loaded with pivot)
     */
    public function getProgressAttribute(): float
    {
        return $this->pivot ? $this->pivot->progress_percentage : 0;
    }

    /**
     * Get total courses count
     */
    public static function getTotalCoursesCount(): int
    {
        return self::count();
    }

    /**
     * Get total published courses count
     */
    public static function getPublishedCoursesCount(): int
    {
        return self::where('is_published', true)->count();
    }

    /**
     * Get total enrollments across all courses
     */
    public static function getTotalEnrollmentsCount(): int
    {
        return self::sum('enrolled_count');
    }
}