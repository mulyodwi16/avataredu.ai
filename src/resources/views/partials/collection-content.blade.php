{{-- My Collection Content --}}
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Collection</h1>
                <p class="text-gray-600 mt-1">Courses you have enrolled in</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-2xl font-bold text-primary">{{ $enrolledCourses->total() ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Total Courses</div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if($enrolledCourses && $enrolledCourses->count() > 0)
            {{-- Courses Grid --}}
            <div id="courses-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($enrolledCourses as $course)
                    <div class="border border-gray-200 rounded-xl p-4">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>

                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $course->title ?? 'Course Title' }}
                                </h3>
                                <p class="text-gray-600 text-sm mb-3">
                                    {{ Str::limit($course->description ?? 'Course description', 100) }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <div class="flex-1 mr-4">
                                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                                            <span>Progress</span>
                                            <span>{{ $course->pivot->progress_percentage ?? 0 }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-primary h-2 rounded-full"
                                                style="width: {{ $course->pivot->progress_percentage ?? 0 }}%"></div>
                                        </div>
                                    </div>

                                    <div class="flex gap-2">
                                        <button onclick="continueLearning({{ $course->id }})"
                                            class="bg-primary text-white px-4 py-2 rounded-lg text-sx hover:bg-primary/90">
                                            Continue Learning
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                <h3 class="text-lg font-medium text-gray-900 mb-2">No enrolled courses yet</h3>
                <p class="text-gray-500 mb-4">Browse our course catalog to start learning</p>
                <button onclick="loadPage('courses')"
                    class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90">
                    Browse Courses
                </button>
            </div>
        @endif
    </div>
</div>