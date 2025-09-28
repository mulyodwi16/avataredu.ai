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
        'social_links',
        'creator_rating',
        'courses_count',
        'students_count'
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
     * Check if user is a creator
     */
    public function isCreator(): bool
    {
        return $this->role === 'creator';
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get the courses created by this user
     */
    public function createdCourses()
    {
        return $this->hasMany(Course::class, 'creator_id');
    }

    /**
     * Get the courses purchased by this user
     */
    public function purchasedCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot(['progress', 'completed_at', 'last_accessed_at'])
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
}
