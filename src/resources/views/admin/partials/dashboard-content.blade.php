{{-- Welcome Section --}}
<section class="bg-gradient-to-r from-primary via-primaryDark to-primary text-white rounded-xl p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="text-white/90 text-sm sm:text-base">Manage your courses and help students learn effectively.</p>
        </div>
        @if(auth()->user()->isSuperAdmin())
            <div class="mt-4 sm:mt-0">
                <button onclick="loadAdminPage('users')"
                    class="bg-white text-primary hover:bg-gray-100 px-4 py-2 rounded-lg font-medium text-sm transition-colors">
                    Manage Users
                </button>
            </div>
        @endif
    </div>
</section>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="p-2 bg-primary rounded-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-gray-600 text-xs font-medium">Total Courses</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['total_courses'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="p-2 bg-gradient-to-r from-primary to-accent rounded-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-gray-600 text-xs font-medium">Published</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['published_courses'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="p-2 bg-gradient-to-br from-primaryDark to-primary rounded-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-gray-600 text-xs font-medium">Drafts</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['draft_courses'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

{{-- My Recent Courses --}}
<section class="mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="p-4 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">My Recent Courses</h3>
                <button onclick="loadAdminPage('courses')"
                    class="text-primary hover:text-primary/80 text-sm font-medium transition-colors flex items-center gap-1">
                    <span>View All</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        @if($recentCourses && $recentCourses->count() > 0)
            <div class="p-4">
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($recentCourses as $course)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="font-medium text-gray-900 text-sm leading-tight">{{ Str::limit($course->title, 40) }}
                                </h4>
                                <span
                                    class="text-xs px-2 py-1 rounded-full font-medium {{ $course->is_published ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }} ml-2 flex-shrink-0">
                                    {{ $course->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ Str::limit($course->description, 60) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">{{ $course->updated_at->format('M d, Y') }}</span>
                                <div class="flex gap-2">
                                    <button onclick="loadAdminPage('courses/{{ $course->id }}')"
                                        class="text-xs px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition-colors">View</button>
                                    <button onclick="loadAdminPage('courses/{{ $course->id }}/edit')"
                                        class="text-xs px-2 py-1 bg-primary hover:bg-primary/90 text-white rounded transition-colors">Edit</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="p-8 text-center">
                <div class="w-12 h-12 mx-auto mb-4 text-gray-300">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h4 class="text-base font-medium text-gray-900 mb-2">No courses created yet</h4>
                <p class="text-sm text-gray-600 mb-4">Start creating your first course to help students learn</p>
                <a href="/admin/createcourse"
                    class="inline-block bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 text-sm font-medium transition-colors">
                    Create Your First Course
                </a>
            </div>
        @endif
    </div>
</section>

{{-- Quick Actions --}}
<section>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="p-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <a href="/admin/createcourse"
                    class="flex items-center justify-center gap-2 bg-primary text-white px-4 py-3 rounded-lg hover:bg-primary/90 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Create New Course
                </a>
                <button onclick="loadAdminPage('courses')"
                    class="flex items-center justify-center gap-2 bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Manage All Courses
                </button>
                @if(auth()->user()->isSuperAdmin())
                    <button onclick="loadAdminPage('users')"
                        class="flex items-center justify-center gap-2 bg-purple-600 text-white px-4 py-3 rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                        Manage Users
                    </button>
                @endif
            </div>
        </div>
    </div>
</section>