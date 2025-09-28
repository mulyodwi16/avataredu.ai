<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'address',
        'city',
        'country',
        'phone',
        'email',
        'website',
        'logo',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_institutions')
            ->withPivot(['department_id', 'student_id', 'status', 'metadata'])
            ->withTimestamps();
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'institution_courses')
            ->withPivot(['department_id', 'is_mandatory', 'available_from', 'available_until'])
            ->withTimestamps();
    }
}