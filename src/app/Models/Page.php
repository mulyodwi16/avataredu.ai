<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'course_id',
    ];

    /**
     * Get the route key for implicit model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the course that this page belongs to
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Check if page is published (based on course status)
     */
    public function isPublished(): bool
    {
        if (!$this->course) {
            return false;
        }
        return $this->course->is_published;
    }

    /**
     * Scope to get only published pages (based on course status)
     */
    public function scopePublished($query)
    {
        return $query->whereHas('course', function ($q) {
            $q->where('is_published', true);
        });
    }

    /**
     * Scope to get only draft pages (based on course status)
     */
    public function scopeDraft($query)
    {
        return $query->whereHas('course', function ($q) {
            $q->where('is_published', false);
        })->orWhereNull('course_id');
    }
}
