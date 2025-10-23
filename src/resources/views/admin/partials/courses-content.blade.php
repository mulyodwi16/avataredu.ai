{{-- Manage Courses Content --}}
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Manage Courses</h2>
                <p class="text-gray-600 text-sm mt-1">Create and manage your course content</p>
            </div>
            
            <!-- Filters & Actions -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                <!-- Search -->
                <div class="relative">
                    <input type="search" placeholder="Search courses..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm w-full sm:w-64 focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                
                <!-- Status Filter -->
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <option value="">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
                
                <!-- Create Button -->
                <a href="/admin/dashboard/createcourse"
                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create Course
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if($courses && (is_array($courses) ? count($courses) > 0 : $courses->count() > 0))
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100">
                        <!-- Course Thumbnail -->
                        <div class="relative overflow-hidden rounded-t-2xl">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}"
                                    class="w-full h-44 object-cover">
                            @else
                                <div class="w-full h-44 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                            @endif
                            <!-- Status Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="text-xs px-2 py-1 rounded-full font-medium {{ $course->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $course->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </div>
                            <!-- Price Badge -->
                            @if($course->price > 0)
                                <div class="absolute top-3 right-3">
                                    <span class="text-xs px-2 py-1 rounded-full bg-white/90 text-gray-800 font-semibold">
                                        Rp {{ number_format($course->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            @else
                                <div class="absolute top-3 right-3">
                                    <span class="text-xs px-2 py-1 rounded-full bg-green-500 text-white font-semibold">
                                        Free
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Course Content -->
                        <div class="p-5">
                            <div class="mb-3">
                                <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 text-lg">{{ $course->title }}</h3>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($course->description, 80) }}</p>
                            </div>

                            <!-- Course Stats -->
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $course->enrolled_count ?? 0 }} students
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $course->duration_hours }}h
                                    </span>
                                </div>
                                <span>{{ $course->updated_at->format('M d, Y') }}</span>
                            </div>

                            <!-- Creator Info (untuk superadmin) -->
                            @if(auth()->user()->isSuperAdmin() && $course->creator_id !== auth()->id())
                                <div class="mb-3 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                        Created by: {{ $course->creator->name ?? 'Unknown' }}
                                    </span>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                @if($course->creator_id === auth()->id())
                                    <!-- Admin bisa edit course mereka sendiri -->
                                    <a href="/admin/dashboard/editcourse/{{ $course->id }}"
                                        class="flex-1 text-sm px-3 py-2 bg-primary text-white hover:bg-primary/90 rounded-lg font-medium transition-colors text-center">
                                        Edit
                                    </a>
                                    <button onclick="deleteCourse({{ $course->id }})"
                                        class="text-sm px-3 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg font-medium transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                @elseif(auth()->user()->isSuperAdmin())
                                    <!-- Superadmin bisa edit dan hapus course siapa saja -->
                                    <a href="/admin/dashboard/editcourse/{{ $course->id }}"
                                        class="flex-1 text-sm px-3 py-2 bg-primary text-white hover:bg-primaryDark rounded-lg font-medium transition-colors text-center">
                                        Edit (Super)
                                    </a>
                                    <button onclick="deleteCourse({{ $course->id }})"
                                        class="text-sm px-3 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg font-medium transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                @else
                                    <!-- Admin lain tidak bisa edit -->
                                    <span class="flex-1 text-sm px-3 py-2 bg-gray-300 text-gray-500 rounded-lg font-medium text-center cursor-not-allowed">
                                        No Access
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if(is_object($courses) && method_exists($courses, 'hasPages') && $courses->hasPages())
                <div class="mt-6">
                    @if(is_object($courses) && method_exists($courses, 'links'))
                        {{ $courses->links() }}
                    @endif
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="text-gray-500 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Courses Found</h3>
                <p class="text-gray-600 mb-6">Get started by creating your first course.</p>
                <a href="/admin/dashboard/createcourse"
                    class="inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90">
                    Create Your First Course
                </a>
            </div>
        @endif
    </div>
</div>