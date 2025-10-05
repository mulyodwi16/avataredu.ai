{{-- Dashboard Statistics --}}
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
            <div class="p-3 bg-accent/10 rounded-full">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="p-3 bg-accent/20 rounded-full">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>
</section>

{{-- Welcome Message --}}
<section class="bg-gradient-to-r from-primary to-accent text-white rounded-xl p-8 mb-8">
    <h2 class="text-2xl font-bold mb-2">Welcome back, {{ $user->name }}!</h2>
    <p class="text-white/90 mb-4">Continue your learning journey and achieve your goals.</p>
    <button onclick="loadPage('courses')"
        class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium">
        Browse More Courses
    </button>
</section>

{{-- Recent Learning --}}
<section>
    <div class="mb-6 flex items-center justify-between">
        <h3 class="text-xl font-bold text-gray-800">Continue Learning</h3>
        <button onclick="loadPage('collection')" class="text-primary hover:underline text-sm font-medium">View
            all</button>
    </div>

    @if(isset($recentCourses) && $recentCourses->count() > 0)
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($recentCourses as $course)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-primary to-accent rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $course->title }}</h4>
                            <p class="text-sm text-gray-600">{{ $course->progress ?? 0 }}% complete</p>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                <div class="bg-primary h-2 rounded-full" style="width: {{ $course->progress ?? 0 }}%"></div>
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
            <h4 class="text-lg font-medium text-gray-900 mb-2">No courses yet</h4>
            <p class="text-gray-500 mb-4">Start your learning journey by browsing our courses</p>
            <button onclick="loadPage('courses')"
                class="inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90">
                Browse Courses
            </button>
        </div>
    @endif
</section>