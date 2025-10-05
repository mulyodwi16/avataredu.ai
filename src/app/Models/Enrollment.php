<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'transaction_id',
        'progress_percentage',
        'completed_lessons',
        'last_accessed_at',
        'completed_at'
    ];

    protected $casts = [
        'completed_lessons' => 'json',
        'progress_percentage' => 'decimal:2',
        'last_accessed_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }

    public function getProgressAttribute(): float
    {
        return (float) ($this->progress_percentage ?? 0);
    }
}
