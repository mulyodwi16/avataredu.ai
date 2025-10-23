@extends('layouts.admin-dashboard')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
    <div data-admin-content>
        {{-- Welcome Section --}}
        <section class="bg-gradient-to-r from-primary to-primaryDark text-white rounded-2xl p-8">
            <h2 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="text-white/90 mb-4">Manage your courses and help students learn effectively.</p>
            <div class="flex space-x-4">
                <button onclick="loadAdminPage('courses/create')"
                    class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium">
                    Create New Course
                </button>
                <button onclick="loadAdminPage('courses')"
                    class="bg-white text-primary hover:bg-gray-100 px-4 py-2 rounded-lg font-medium">
                    Manage Courses
                </button>
            </div>
        </section>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="bg-white p-6 rounded-xl shadow">
                <div class="flex items-center">
                    <div class="p-2 bg-primary/10 rounded-lg">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm">My Courses</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_courses'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <div class="flex items-center">
                    <div class="p-2 bg-accent/10 rounded-lg">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm">Total Students</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_students'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm">Published</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['published_courses'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm">Drafts</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['draft_courses'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm">Enrollments</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_enrollments'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Courses --}}
        <section>
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800">My Recent Courses</h3>
                <button onclick="loadAdminPage('courses')" class="text-primary hover:underline text-sm font-medium">Manage
                    all</button>
            </div>

            @if($recentCourses && $recentCourses->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recentCourses as $course)
                        <div class="bg-white rounded-2xl shadow hover:shadow-lg transition-all duration-300">
                            <div class="relative overflow-hidden rounded-t-2xl">
                                <img src="{{ $course->thumbnail ?? 'https://via.placeholder.com/400x240.png?text=Course' }}"
                                    alt="{{ $course->title }}" class="w-full h-44 object-cover">
                                <div class="absolute top-2 left-2">
                                    <span
                                        class="text-xs px-2 py-1 rounded-full 
                                                                                                                                    {{ $course->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $course->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    @if($course->category)
                                        <span class="text-xs px-2 py-1 rounded-full bg-primary/10 text-primary font-medium">
                                            {{ $course->category->name }}
                                        </span>
                                    @endif
                                    <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">
                                        {{ $course->level ?? 'Beginner' }}
                                    </span>
                                </div>
                                <h4 class="font-semibold line-clamp-2 min-h-[48px] mb-2">{{ $course->title }}</h4>
                                <p class="text-gray-500 text-sm mb-3">
                                    {{ $course->students_count ?? 0 }} students •
                                    Updated {{ $course->updated_at->format('M j, Y') }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-primary">
                                        @if($course->price > 0)
                                            Rp {{ number_format($course->price, 0, ',', '.') }}
                                        @else
                                            Free
                                        @endif
                                    </span>
                                    <div class="flex gap-2">
                                        <button onclick="editCourse({{ $course->id }})"
                                            class="text-sm px-3 py-1.5 rounded-lg bg-primary text-white hover:bg-primaryDark flex-1">
                                            Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-xl">
                    <div class="w-16 h-16 mx-auto mb-4 text-gray-300">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">No courses created yet</h4>
                    <p class="text-gray-500 mb-4">Start creating your first course to help students learn</p>
                    <button onclick="loadAdminPage('courses/create')"
                        class="inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-primaryDark">
                        Create Course
                    </button>
                </div>
            @endif
        </section>

        {{-- Popular Courses Overview --}}
        @if($featuredCourses && $featuredCourses->count() > 0)
            <section>
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">Popular Courses on Platform</h3>
                    <button onclick="loadAdminPage('courses')" class="text-primary hover:underline text-sm font-medium">View
                        all</button>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredCourses->take(6) as $course)
                        <x-course-card :course="$course" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection