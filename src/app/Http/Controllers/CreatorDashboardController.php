<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CreatorDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $courses = $user->createdCourses()
            ->withCount('students')
            ->latest()
            ->paginate(6);

        return view('pages.creator.dashboard', compact('courses'));
    }

    public function stats()
    {
        $user = auth()->user();
        $stats = [
            'total_courses' => $user->courses_count,
            'total_students' => $user->students_count,
            'rating' => $user->creator_rating,
            'earnings' => $user->createdCourses()->sum('price')
        ];

        return view('pages.creator.stats', compact('stats'));
    }
}