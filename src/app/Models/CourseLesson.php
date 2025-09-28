<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CourseLesson extends Model
{
    protected $fillable = [
        'chapter_id',
        'title',
        'description',
        'video_url',
        'duration',
        'content',
        'attachments',
        'order'
    ];

    protected $casts = [
        'attachments' => 'json'
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(CourseChapter::class, 'chapter_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lesson_progress')
            ->withPivot(['is_completed', 'completed_at', 'last_accessed_at'])
            ->withTimestamps();
    }
}