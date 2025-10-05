<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonProgress extends Model
{
    protected $table = 'lesson_progress';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'is_completed',
        'watched_duration',
        'total_duration',
        'last_watched_position',
        'completed_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'watched_duration' => 'integer',
        'total_duration' => 'integer',
        'last_watched_position' => 'integer',
        'completed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class);
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->total_duration <= 0) {
            return 0;
        }

        return min(100, (int) round(($this->watched_duration / $this->total_duration) * 100));
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
            'watched_duration' => $this->total_duration,
            'last_watched_position' => $this->total_duration
        ]);
    }
}
