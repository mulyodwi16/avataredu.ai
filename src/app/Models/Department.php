<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'name',
        'code',
        'description'
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'student_institutions', 'department_id');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'institution_courses', 'department_id');
    }
}