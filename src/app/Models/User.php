<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'provider',
        'provider_id',
        'avatar',
        'role',
        'bio',
        'expertise',
        'website',
        'social_links'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'social_links' => 'json',
        ];
    }

    /**
     * Check if user is a regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user' || $this->role === null;
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'super_admin';
    }

    /**
     * Check if user is a super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Get the courses created by this user (for admins)
     */
    public function createdCourses()
    {
        return $this->hasMany(Course::class, 'creator_id');
    }

    /**
     * Alias for createdCourses for better naming consistency
     */
    public function courses()
    {
        return $this->createdCourses();
    }

    /**
     * Get the courses enrolled by this user
     */
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot(['progress_percentage', 'completed_at', 'last_accessed_at'])
            ->withTimestamps();
    }

    /**
     * Get the institutions that the user is enrolled in.
     */
    public function institutions(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'student_institutions')
            ->withPivot(['department_id', 'student_id', 'status', 'metadata'])
            ->withTimestamps();
    }

    /**
     * Get user's departments through student_institutions
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'student_institutions')
            ->withPivot(['student_id', 'status', 'metadata'])
            ->withTimestamps();
    }

    /**
     * Get user's enrollments
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get user's transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get user's lesson progress through enrollments
     */
    public function lessonProgress()
    {
        return $this->hasManyThrough(
            LessonProgress::class,
            Enrollment::class,
            'user_id',        // Foreign key on enrollments table
            'enrollment_id',  // Foreign key on lesson_progress table  
            'id',            // Local key on users table
            'id'             // Local key on enrollments table
        );
    }

    /**
     * Check if user can manage courses (admin only)
     */
    public function canManageCourses(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Get total enrollments count across all courses
     */
    public static function getTotalEnrollmentsCount(): int
    {
        return \App\Models\Enrollment::count();
    }

    /**
     * Get total enrolled students count (unique users with at least one enrollment)
     */
    public static function getTotalStudentsCount(): int
    {
        return \App\Models\Enrollment::distinct('user_id')->count('user_id');
    }
}
