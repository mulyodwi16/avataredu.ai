<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Department;
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
        'price',
        'status',
        'level',
        'duration',
        'metadata',
        'learning_outcomes',
        'requirements',
        'students_count',
        'ratings_count',
        'average_rating',
        'published_at'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'metadata' => 'json'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the creator of this course
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the students enrolled in this course
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot(['progress', 'completed_at', 'last_accessed_at'])
            ->withTimestamps();
    }

    /**
     * Get the chapters for this course
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(CourseChapter::class)->orderBy('order');
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
}