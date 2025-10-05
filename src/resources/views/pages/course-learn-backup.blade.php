@extends('layouts.app')

@section('content')
    @php use Illuminate\Support\Facades\Storage; @endphp
    <div class="min-h-screen bg-gray-100">
        <div class="flex">
            {{-- Course Content Sidebar --}}
            <div class="w-80 bg-white shadow-sm border-r border-gray-200 fixed h-full overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center gap-3 mb-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                                </path>
                            </svg>
                        </a>
                        <h1 class="text-lg font-semibold text-gray-900 truncate">{{ $course->title }}</h1>
                    </div>

                    {{-- Progress Bar --}}
                    @php
                        $totalLessons = $course->chapters->flatMap->lessons->count();

                        if ($totalLessons > 0) {
                            // Chapter/Lesson based course
                            $completedLessons = $lessonProgress->where('is_completed', true)->count();
                            $progressPercentage = round(($completedLessons / $totalLessons) * 100);
                        } else if ($course->main_video_url) {
                            // Simple video course
                            $enrollment = Auth::user()->enrollments()->where('course_id', $course->id)->first();
                            $isCompleted = $enrollment ? $enrollment->isCompleted() : false;
                            $progressPercentage = $isCompleted ? 100 : 0;
                            $completedLessons = $isCompleted ? 1 : 0;
                            $totalLessons = 1;
                        } else {
                            // No content
                            $progressPercentage = 0;
                            $completedLessons = 0;
                            $totalLessons = 0;
                        }
                    @endphp

                    @if($totalLessons > 0)
                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Progress</span>
                                <span>{{ $progressPercentage }}% ({{ $completedLessons }}/{{ $totalLessons }})</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary h-2 rounded-full transition-all duration-300"
                                    style="width: {{ $progressPercentage }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Course Content --}}
                <div class="p-4">
                    @if($course->chapters->count() > 0)
                        {{-- Chapter/Lesson Based Course --}}
                        @foreach($course->chapters as $chapter)
                            <div class="mb-6">
                                <h3 class="font-semibold text-gray-900 mb-3 px-2">{{ $chapter->title }}</h3>

                                @foreach($chapter->lessons as $lesson)
                                    @php
                                        $isCompleted = $lessonProgress->get($lesson->id)?->is_completed ?? false;
                                        $isCurrentLesson = request()->get('lesson') == $lesson->id;
                                    @endphp

                                    <div class="lesson-item mb-2 {{ $isCurrentLesson ? 'bg-primary/10 border-primary' : 'hover:bg-gray-50' }} 
                                                                                        border rounded-lg p-3 cursor-pointer"
                                        onclick="loadLesson({{ $lesson->id }})">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 mt-1">
                                                @if($isCompleted)
                                                    <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div class="w-5 h-5 border-2 border-gray-300 rounded-full"></div>
                                                @endif
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium text-sm text-gray-900">{{ $lesson->title }}</div>
                                                @if($lesson->duration)
                                                    <div class="text-xs text-gray-500 mt-1">{{ $lesson->duration }} min</div>
                                                @endif
                                            </div>

                                            @if($lesson->video_url)
                                                <div class="flex-shrink-0">
                                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M2 6a2 2 0 012-2h6l2 2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @elseif($course->main_video_url)
                        {{-- Simple Video Course --}}
                        @php
                            $enrollment = Auth::user()->enrollments()->where('course_id', $course->id)->first();
                            $isCompleted = $enrollment ? $enrollment->isCompleted() : false;
                        @endphp

                        <div class="bg-primary/10 border-primary border rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 mt-1">
                                    @if($isCompleted)
                                        <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-full"></div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-sm text-gray-900">{{ $course->video_title ?? 'Course Video' }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">Main Course Content</div>
                                </div>

                                <div class="flex-shrink-0">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M2 6a2 2 0 012-2h6l2 2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- No Content --}}
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                            <p class="text-sm">No content available</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Main Content Area --}}
            <div class="flex-1 ml-80">
                <div id="lesson-content" class="p-6">
                    @if($course->chapters->flatMap->lessons->count() > 0)
                        @php
                            $currentLessonId = request()->get('lesson') ?? $course->chapters->first()?->lessons->first()?->id;
                            $currentLesson = $course->chapters->flatMap->lessons->where('id', $currentLessonId)->first();
                        @endphp

                        @if($currentLesson)
                            {{-- Lesson Header --}}
                            <div class="mb-6">
                                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $currentLesson->title }}</h1>
                                @if($currentLesson->description)
                                    <p class="text-gray-600">{{ $currentLesson->description }}</p>
                                @endif
                            </div>

                            {{-- Video Player --}}
                            @if($currentLesson->video_url)
                                <div class="mb-6 bg-black rounded-lg overflow-hidden">
                                    <video id="lesson-video" class="w-full" controls controlsList="nodownload">
                                        <source src="{{ $currentLesson->video_url }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @endif

                            {{-- Lesson Content --}}
                            @if($currentLesson->content)
                                <div class="bg-white rounded-lg p-6 shadow-sm mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Lesson Content</h3>
                                    <div class="prose max-w-none">
                                        {!! nl2br(e($currentLesson->content)) !!}
                                    </div>
                                </div>
                            @endif

                            {{-- Attachments --}}
                            @if($currentLesson->attachments)
                                <div class="bg-white rounded-lg p-6 shadow-sm mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Attachments</h3>
                                    <div class="space-y-2">
                                        @foreach($currentLesson->attachments as $attachment)
                                            <a href="{{ $attachment['url'] ?? '#' }}" target="_blank"
                                                class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-medium text-gray-900">{{ $attachment['name'] ?? 'Download' }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Mark as Complete Button --}}
                            <div class="flex justify-between items-center">
                                @php
                                    $isCompleted = $lessonProgress->get($currentLesson->id)?->is_completed ?? false;
                                @endphp

                                <button onclick="toggleLessonComplete({{ $currentLesson->id }})"
                                    class="px-6 py-3 rounded-lg font-semibold transition-colors
                                                                               {{ $isCompleted ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-primary text-white hover:bg-primaryDark' }}">
                                    {{ $isCompleted ? 'Completed ✓' : 'Mark as Complete' }}
                                </button>

                                {{-- Navigation Buttons --}}
                                <div class="flex gap-3">
                                    @php
                                        $allLessons = $course->chapters->flatMap->lessons;
                                        $currentIndex = $allLessons->search(fn($lesson) => $lesson->id == $currentLesson->id);
                                        $prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
                                        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;
                                    @endphp

                                    @if($prevLesson)
                                        <button onclick="loadLesson({{ $prevLesson->id }})"
                                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                            Previous
                                        </button>
                                    @endif

                                    @if($nextLesson)
                                        <button onclick="loadLesson({{ $nextLesson->id }})"
                                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primaryDark">
                                            Next
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <p class="text-gray-500">Select a lesson from the sidebar to start learning.</p>
                            </div>
                        @endif
                    @else
                        {{-- Check if course has simple video upload --}}
                        @if($course->main_video_url)
                            {{-- Simple Video Display --}}
                            <div class="mb-6">
                                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $course->video_title ?? $course->title }}</h1>
                                <p class="text-gray-600 mb-6">{{ $course->description }}</p>
                            </div>

                            {{-- Video Player --}}
                            <div class="mb-6 bg-black rounded-lg overflow-hidden">
                                <video id="course-video" class="w-full" controls controlsList="nodownload">
                                    <source src="{{ asset('storage/' . $course->main_video_url) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>

                            {{-- Course Info --}}
                            <div class="bg-white rounded-lg p-6 shadow-sm mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">About This Course</h3>
                                <div class="prose max-w-none">
                                    <p class="text-gray-600">{{ $course->description }}</p>
                                    @if($course->instructor)
                                        <p class="text-sm text-gray-500 mt-4">
                                            <strong>Instructor:</strong> {{ $course->instructor }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Mark Course as Complete Button --}}
                            <div class="flex justify-center">
                                @php
                                    $enrollment = Auth::user()->enrollments()->where('course_id', $course->id)->first();
                                    $isCompleted = $enrollment ? $enrollment->isCompleted() : false;
                                @endphp

                                <button onclick="toggleCourseComplete({{ $course->id }})"
                                    class="px-8 py-3 rounded-lg font-semibold transition-colors
                                                                                   {{ $isCompleted ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-primary text-white hover:bg-primaryDark' }}">
                                    {{ $isCompleted ? 'Course Completed ✓' : 'Mark Course as Complete' }}
                                </button>
                            </div>
                        @else
                            {{-- No Content Available --}}
                            <div class="text-center py-12">
                                <div class="w-16 h-16 mx-auto mb-4 text-gray-300">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No content available</h3>
                                <p class="text-gray-500">This course doesn't have any lessons yet.</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
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
                        button.textContent = isCompleted ? 'Completed ✓' : 'Mark as Complete';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating lesson progress');
                    button.disabled = false;
                    button.textContent = isCompleted ? 'Completed ✓' : 'Mark as Complete';
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
                        button.textContent = isCompleted ? 'Course Completed ✓' : 'Mark Course as Complete';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating course progress');
                    button.disabled = false;
                    button.textContent = isCompleted ? 'Course Completed ✓' : 'Mark Course as Complete';
                });
        }
    </script>
@endsection