@extends('layouts.user-dashboard')

@section('title', 'Dashboard')
@section('page-title', 'My Dashboard')

@section('content')
    {{-- Dashboard Content --}}
    <div class="space-y-8" data-content>

        {{-- Hero Banner --}}
        <section class="bg-night/90 text-white rounded-2xl shadow overflow-hidden relative">
            <div class="grid md:grid-cols-[1fr,1.3fr]">
                <div class="p-8 md:p-10">
                    <h2 class="text-2xl md:text-3xl font-extrabold mb-3">
                        Unlock Your Career Potential with Kirana!
                    </h2>
                    <p class="text-white/80 mb-6">
                        Explore a career path tailored to your skills and ambitions. Get a personalized roadmap and
                        course suggestions to achieve your goals.
                    </p>
                    <a href="#"
                        class="inline-block bg-accent text-white font-semibold px-5 py-2 rounded-xl hover:opacity-90">
                        Click here to start!
                    </a>
                </div>
                <div class="relative p-6 md:p-8">
                    <img src="https://via.placeholder.com/520x260.png?text=Hero+Banner"
                        class="w-full h-56 md:h-full object-cover rounded-xl" alt="hero">
                    {{-- decorative --}}
                    <div class="hidden md:block absolute -right-6 -bottom-6 w-40 h-40 rounded-full bg-accent/30 blur-2xl">
                    </div>
                </div>
            </div>
        </section>

        {{-- Learning Statistics --}}
        <section class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Enrolled Courses</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['enrolled_courses'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-primary/10 rounded-full">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['completed_courses'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-gradient-to-r from-primary/20 to-accent/20 rounded-full">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">In Progress</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['in_progress_courses'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-primaryDark/10 rounded-full">
                        <svg class="w-6 h-6 text-primaryDark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Learning Hours</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['learning_hours'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-accent/10 rounded-full">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        {{-- My Learning Progress --}}
        <section>
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800">My Learning Progress</h3>
                <button onclick="loadPage('courses')" class="text-primary hover:underline text-sm font-medium">Browse
                    more</button>
            </div>

            @if($recentCourses && $recentCourses->count() > 0)
                <div
                    class="flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2
                                                                                    md:grid md:grid-cols-3 xl:grid-cols-4 md:overflow-visible md:snap-none">
                    @foreach($recentCourses as $course)
                        <x-course-card :course="$course" :show-progress="true" :progress="$course->pivot->progress_percentage ?? 0"
                            class="min-w-[280px] snap-start md:min-w-0" />
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 text-gray-300">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">No courses enrolled yet</h4>
                    <p class="text-gray-500 mb-4">Start learning by browsing our course catalog</p>
                    <button onclick="loadPage('courses')"
                        class="inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-primaryDark">
                        Browse Courses
                    </button>
                </div>
            @endif
        </section>

        {{-- Recommendations --}}
        @if(isset($recommendations) && $recommendations->count() > 0)
            <section>
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">Recommended for You</h3>
                </div>

                <div
                    class="flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2
                                                                                md:grid md:grid-cols-3 xl:grid-cols-4 md:overflow-visible md:snap-none">
                    @foreach($recommendations as $course)
                        <x-course-card :course="$course" class="min-w-[280px] snap-start md:min-w-0" />
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Featured Courses --}}
        @if(isset($featuredCourses) && $featuredCourses->count() > 0)
            <section>
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">Popular Courses</h3>
                    <button onclick="loadPage('courses')" class="text-primary hover:underline text-sm font-medium">View
                        all</button>
                </div>

                <div
                    class="flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2
                                                                                md:grid md:grid-cols-3 xl:grid-cols-4 md:overflow-visible md:snap-none">
                    @foreach($featuredCourses as $course)
                        <x-course-card :course="$course" class="min-w-[280px] snap-start md:min-w-0" />
                    @endforeach
                </div>
            </section>
        @endif

    </div>
@endsection