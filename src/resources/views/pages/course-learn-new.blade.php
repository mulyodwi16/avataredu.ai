@extends('layouts.app')

@section('title', 'Learn: ' . $course->title)

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Mobile Header -->
        <div class="lg:hidden bg-white shadow-sm border-b p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <button id="mobile-menu-toggle"
                        class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold text-gray-900 truncate">{{ $course->title }}</h1>
                </div>
                <a href="{{ route('dashboard') }}"
                    class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </a>
            </div>
        </div>

        <div class="flex relative">
            <!-- Sidebar -->
            <div id="sidebar"
                class="w-80 bg-white shadow-xl fixed lg:relative h-full lg:h-screen overflow-y-auto z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
                <!-- Sidebar Header -->
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold">{{ $course->title }}</h2>
                        <a href="{{ route('dashboard') }}" class="hidden lg:block text-white/80 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Enhanced Progress Bar -->
                    @php
                        if ($course->chapters->flatMap->lessons->count() > 0) {
                            $totalLessons = $course->chapters->flatMap->lessons->count();
                            $completedLessons = $lessonProgress->where('is_completed', true)->count();
                            $progressPercentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
                        } else if ($course->main_video_url) {
                            // Simple video course
                            $enrollment = Auth::user()->enrollments()->where('course_id', $course->id)->first();
                            $progressPercentage = ($enrollment && $enrollment->isCompleted()) ? 100 : 0;
                            $completedLessons = $progressPercentage == 100 ? 1 : 0;
                            $totalLessons = 1;
                        } else {
                            $progressPercentage = 0;
                            $completedLessons = 0;
                            $totalLessons = 0;
                        }
                    @endphp

                    <div class="mb-2">
                        <div class="flex justify-between text-sm text-white/90 mb-3">
                            <span class="font-medium">Course Progress</span>
                            <span
                                class="bg-white/20 px-2 py-1 rounded-full text-xs font-semibold">{{ round($progressPercentage) }}%</span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-3">
                            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-3 rounded-full transition-all duration-500 ease-out"
                                style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <div class="text-xs text-white/80 mt-2 text-center">
                            {{ $completedLessons }} of {{ $totalLessons }} {{ $totalLessons == 1 ? 'item' : 'lessons' }}
                            completed
                        </div>
                    </div>
                </div>

                <!-- Course Content -->
                <div class="p-6">
                    @if($course->chapters->count() > 0)
                        @foreach($course->chapters as $chapter)
                            <div class="mb-8">
                                <h3 class="font-bold text-gray-900 mb-4 text-lg border-b border-gray-100 pb-2">
                                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                        {{ $chapter->title }}
                                    </span>
                                </h3>
                                <div class="space-y-3">
                                    @foreach($chapter->lessons as $lesson)
                                        @php
                                            $isCompleted = $lessonProgress->get($lesson->id)?->is_completed ?? false;
                                            $isCurrent = request()->get('lesson') == $lesson->id ||
                                                (request()->get('lesson') === null && $loop->parent->first && $loop->first);
                                        @endphp

                                        <div class="group flex items-center gap-4 p-4 rounded-xl cursor-pointer transition-all duration-200
                                                                    {{ $isCurrent ? 'bg-gradient-to-r from-blue-50 to-purple-50 border-2 border-blue-200 shadow-md transform scale-[1.02]' : 'hover:bg-gray-50 hover:shadow-sm border border-transparent' }}"
                                            onclick="loadLesson({{ $lesson->id }})">
                                            <!-- Enhanced Completion Icon -->
                                            <div class="flex-shrink-0">
                                                @if($isCompleted)
                                                    <div
                                                        class="w-6 h-6 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center shadow-lg">
                                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                @elseif($isCurrent)
                                                    <div
                                                        class="w-6 h-6 border-2 border-primary rounded-full bg-white flex items-center justify-center">
                                                        <div class="w-2 h-2 bg-primary rounded-full"></div>
                                                    </div>
                                                @else
                                                    <div
                                                        class="w-6 h-6 border-2 border-gray-300 rounded-full group-hover:border-primary transition-colors">
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Enhanced Lesson Info -->
                                            <div class="flex-1 min-w-0">
                                                <div
                                                    class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors truncate">
                                                    {{ $lesson->title }}
                                                </div>
                                                <div class="flex items-center gap-3 mt-1">
                                                    @if($lesson->duration)
                                                        <div class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                                            {{ $lesson->duration }}
                                                        </div>
                                                    @endif
                                                    @if($lesson->video_url)
                                                        <div
                                                            class="flex items-center gap-1 text-xs text-primary bg-primary/10 px-2 py-1 rounded-full">
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
                                                                </path>
                                                            </svg>
                                                            Video
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @elseif($course->main_video_url)
                        <!-- Enhanced Simple Video Course -->
                        <div class="mb-6">
                            <h3 class="font-bold text-gray-900 mb-4 text-lg border-b border-gray-100 pb-2">
                                <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                    Course Content
                                </span>
                            </h3>
                            <div class="space-y-3">
                                @php
                                    $enrollment = Auth::user()->enrollments()->where('course_id', $course->id)->first();
                                    $isCompleted = $enrollment ? $enrollment->isCompleted() : false;
                                @endphp

                                <div
                                    class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-blue-50 to-purple-50 border-2 border-blue-200 shadow-md">
                                    <!-- Enhanced Completion Icon -->
                                    <div class="flex-shrink-0">
                                        @if($isCompleted)
                                            <div
                                                class="w-6 h-6 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center shadow-lg">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div
                                                class="w-6 h-6 border-2 border-blue-400 rounded-full bg-white flex items-center justify-center">
                                                <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Enhanced Course Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-900 truncate">
                                            {{ $course->video_title ?? 'Course Video' }}</div>
                                        <div
                                            class="inline-flex items-center gap-1 text-xs text-primary bg-primary/10 px-2 py-1 rounded-full mt-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
                                                </path>
                                            </svg>
                                            Video Content
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 text-gray-300">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">No content available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Overlay for mobile -->
            <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>

            <!-- Main Content Area -->
            <div class="flex-1 lg:ml-80 min-h-screen">
                <div id="lesson-content" class="p-4 lg:p-8">
                    @if($course->chapters->flatMap->lessons->count() > 0)
                        @php
                            $currentLessonId = request()->get('lesson') ?? $course->chapters->first()?->lessons->first()?->id;
                            $currentLesson = $course->chapters->flatMap->lessons->where('id', $currentLessonId)->first();
                        @endphp

                        @if($currentLesson)
                            <!-- Enhanced Lesson Header -->
                            <div class="mb-8">
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
                                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">{{ $currentLesson->title }}</h1>
                                    @if($currentLesson->description)
                                        <p class="text-lg text-gray-600 leading-relaxed">{{ $currentLesson->description }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Enhanced Video Player -->
                            @if($currentLesson->video_url)
                                <div class="mb-8">
                                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                                        <div class="aspect-video bg-black relative">
                                            <video id="lesson-video" class="w-full h-full object-contain" controls
                                                controlsList="nodownload"
                                                poster="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : '' }}">
                                                <source src="{{ $currentLesson->video_url }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Enhanced Lesson Content -->
                            @if($currentLesson->content)
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8 mb-8">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        Lesson Content
                                    </h3>
                                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                        {!! nl2br(e($currentLesson->content)) !!}
                                    </div>
                                </div>
                            @endif

                            <!-- Enhanced Attachments -->
                            @if($currentLesson->attachments)
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8 mb-8">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-green-500 to-teal-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        Attachments
                                    </h3>
                                    <div class="grid gap-3">
                                        @foreach($currentLesson->attachments as $attachment)
                                            <a href="{{ $attachment['url'] ?? '#' }}" target="_blank"
                                                class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl hover:border-primary hover:bg-primary/5 transition-all duration-200 group">
                                                <div
                                                    class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <span
                                                    class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $attachment['name'] ?? 'Download' }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Enhanced Action Buttons -->
                            <div
                                class="flex flex-col lg:flex-row justify-between items-center gap-6 bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
                                @php
                                    $isCompleted = $lessonProgress->get($currentLesson->id)?->is_completed ?? false;
                                @endphp

                                <button onclick="toggleLessonComplete({{ $currentLesson->id }})"
                                    class="w-full lg:w-auto px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-opacity-50
                                                       {{ $isCompleted ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:from-green-600 hover:to-emerald-700 focus:ring-green-300 shadow-lg' : 'bg-gradient-to-r from-blue-600 to-purple-600 text-white hover:from-blue-700 hover:to-purple-700 focus:ring-blue-300 shadow-lg' }}">
                                    {{ $isCompleted ? '✓ Completed' : 'Mark as Complete' }}
                                </button>

                                <!-- Enhanced Navigation Buttons -->
                                <div class="flex gap-3 w-full lg:w-auto">
                                    @php
                                        $allLessons = $course->chapters->flatMap->lessons;
                                        $currentIndex = $allLessons->search(fn($lesson) => $lesson->id == $currentLesson->id);
                                        $prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
                                        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;
                                    @endphp

                                    @if($prevLesson)
                                        <button onclick="loadLesson({{ $prevLesson->id }})"
                                            class="flex-1 lg:flex-none px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:border-primary hover:bg-primary/5 transition-all duration-200 font-semibold">
                                            ← Previous
                                        </button>
                                    @endif

                                    @if($nextLesson)
                                        <button onclick="loadLesson({{ $nextLesson->id }})"
                                            class="flex-1 lg:flex-none px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 font-semibold shadow-md">
                                            Next →
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-center py-20">
                                <div class="w-20 h-20 mx-auto mb-6 text-gray-300">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">Select a lesson to start learning</h3>
                                <p class="text-gray-500 text-lg">Choose a lesson from the sidebar to begin your learning journey.
                                </p>
                            </div>
                        @endif
                    @else
                        <!-- Enhanced Simple Video Course Display -->
                        @if($course->main_video_url)
                            <div class="mb-8">
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
                                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                                        {{ $course->video_title ?? $course->title }}</h1>
                                    <p class="text-lg text-gray-600 leading-relaxed mb-6">{{ $course->description }}</p>
                                </div>
                            </div>

                            <!-- Enhanced Video Player for Simple Course -->
                            <div class="mb-8">
                                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                                    <div class="aspect-video bg-black relative">
                                        <video id="course-video" class="w-full h-full object-contain" controls
                                            controlsList="nodownload"
                                            poster="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : '' }}">
                                            <source src="{{ asset('storage/' . $course->main_video_url) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div>
                            </div>

                            <!-- Enhanced Course Info -->
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8 mb-8">
                                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    About This Course
                                </h3>
                                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                    <p>{{ $course->description }}</p>
                                    @if($course->instructor)
                                        <div
                                            class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl border border-blue-100">
                                            <p class="text-sm font-semibold text-gray-900 mb-1">Instructor</p>
                                            <p class="text-blue-600 font-medium">{{ $course->instructor }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Enhanced Course Completion Button -->
                            <div class="flex justify-center">
                                @php
                                    $enrollment = Auth::user()->enrollments()->where('course_id', $course->id)->first();
                                    $isCompleted = $enrollment ? $enrollment->isCompleted() : false;
                                @endphp

                                <button onclick="toggleCourseComplete({{ $course->id }})"
                                    class="px-12 py-4 rounded-xl font-bold text-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-opacity-50
                                                       {{ $isCompleted ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:from-green-600 hover:to-emerald-700 focus:ring-green-300 shadow-lg' : 'bg-gradient-to-r from-blue-600 to-purple-600 text-white hover:from-blue-700 hover:to-purple-700 focus:ring-blue-300 shadow-lg' }}">
                                    {{ $isCompleted ? '✓ Course Completed' : 'Mark Course as Complete' }}
                                </button>
                            </div>
                        @else
                            <!-- Enhanced No Content Available -->
                            <div class="text-center py-20">
                                <div class="w-24 h-24 mx-auto mb-6 text-gray-300">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-3xl font-bold text-gray-900 mb-4">No content available</h3>
                                <p class="text-gray-500 text-lg">This course doesn't have any lessons or content yet.</p>
                                <div class="mt-8">
                                    <a href="{{ route('dashboard') }}"
                                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                                        Return to Dashboard
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-toggle')?.addEventListener('click', function () {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        // Close sidebar when clicking overlay
        document.getElementById('sidebar-overlay')?.addEventListener('click', function () {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        function loadLesson(lessonId) {
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('lesson', lessonId);
            window.location.href = currentUrl.toString();
        }

        function toggleLessonComplete(lessonId) {
            const button = event.target;
            const isCompleted = button.textContent.includes('Completed');

            // Show loading
            button.disabled = true;
            button.textContent = 'Processing...';

            fetch(`/api/lessons/${lessonId}/${isCompleted ? 'incomplete' : 'complete'}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page to update progress
                        window.location.reload();
                    } else {
                        alert('Error updating lesson progress');
                        button.disabled = false;
                        button.textContent = isCompleted ? '✓ Completed' : 'Mark as Complete';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating lesson progress');
                    button.disabled = false;
                    button.textContent = isCompleted ? '✓ Completed' : 'Mark as Complete';
                });
        }

        function toggleCourseComplete(courseId) {
            const button = event.target;
            const isCompleted = button.textContent.includes('Completed');

            // Show loading
            button.disabled = true;
            button.textContent = 'Processing...';

            fetch(`/api/courses/${courseId}/${isCompleted ? 'incomplete' : 'complete'}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page to update progress
                        window.location.reload();
                    } else {
                        alert('Error updating course progress');
                        button.disabled = false;
                        button.textContent = isCompleted ? '✓ Course Completed' : 'Mark Course as Complete';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating course progress');
                    button.disabled = false;
                    button.textContent = isCompleted ? '✓ Course Completed' : 'Mark Course as Complete';
                });
        }

        // Video player enhancements
        document.addEventListener('DOMContentLoaded', function () {
            const videos = document.querySelectorAll('video');
            videos.forEach(video => {
                video.addEventListener('loadstart', function () {
                    console.log('Video loading started');
                });

                video.addEventListener('canplay', function () {
                    console.log('Video can start playing');
                });

                video.addEventListener('error', function (e) {
                    console.error('Video error:', e);
                    // You could show a user-friendly error message here
                });
            });
        });
    </script>
@endsection