{{-- Course Detail Content --}}
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">
        {{-- Course Header --}}
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Course Image --}}
            <div class="lg:w-1/2">
                @if($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}"
                        class="w-full h-64 lg:h-80 object-cover rounded-xl">
                @else
                    <div
                        class="w-full h-64 lg:h-80 bg-gradient-to-br from-primary/20 to-primary/5 rounded-xl flex items-center justify-center">
                        <span class="text-4xl text-primary/40">📚</span>
                    </div>
                @endif
            </div>

            {{-- Course Info --}}
            <div class="lg:w-1/2 space-y-6">
                {{-- Category --}}
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-sm font-medium">
                        {{ $course->category->name ?? 'General' }}
                    </span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                        {{ ucfirst($course->level) }}
                    </span>
                </div>

                {{-- Title --}}
                <h1 class="text-3xl font-bold text-gray-900">{{ $course->title }}</h1>

                {{-- Creator --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                        <span class="text-primary font-semibold text-sm">
                            {{ substr($course->creator->name, 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $course->creator->name }}</p>
                        <p class="text-sm text-gray-600">Instructor</p>
                    </div>
                </div>

                {{-- Course Stats --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-primary">{{ $course->enrolled_count ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Students</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-primary">{{ $course->duration_hours ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-600">Hours</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-primary">⭐
                            {{ number_format($course->average_rating ?? 0, 1) }}
                        </div>
                        <div class="text-sm text-gray-600">Rating</div>
                    </div>
                </div>

                {{-- Price & Enroll --}}
                <div class="border-t pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            @if($course->price == 0)
                                <div class="text-3xl font-bold text-green-600">Free</div>
                            @else
                                <div class="text-3xl font-bold text-primary">Rp
                                    {{ number_format($course->price, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Check if already enrolled --}}
                    @php
                        $isEnrolled = auth()->user()->enrollments()->where('course_id', $course->id)->exists();
                    @endphp

                    @if($isEnrolled)
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 text-green-600 mb-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">Already Enrolled</span>
                            </div>
                            <button onclick="continueLearning({{ $course->id }})"
                                class="w-full bg-accent text-white py-3 px-6 rounded-lg font-semibold hover:bg-accent/90 transition-colors">
                                Continue Learning
                            </button>
                        </div>
                    @else
                        @if($course->price == 0)
                            <button onclick="enrollFree({{ $course->id }})"
                                class="w-full bg-accent text-white py-3 px-6 rounded-lg font-semibold hover:bg-accent/90 transition-colors">
                                Enroll for Free
                            </button>
                        @else
                            <button onclick="enrollPaid({{ $course->id }})"
                                class="w-full bg-primary text-white py-3 px-6 rounded-lg font-semibold hover:bg-primaryDark transition-colors">
                                Enroll Now
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- Course Content Tabs --}}
        <div class="mt-12">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button onclick="showTab('overview')"
                        class="course-tab active border-b-2 border-primary text-primary py-4 px-1 text-sm font-medium"
                        data-tab="overview">
                        Overview
                    </button>
                    <button onclick="showTab('curriculum')"
                        class="course-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium"
                        data-tab="curriculum">
                        Curriculum
                    </button>
                    <button onclick="showTab('reviews')"
                        class="course-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium"
                        data-tab="reviews">
                        Reviews
                    </button>
                </nav>
            </div>

            {{-- Tab Content --}}
            <div class="mt-8">
                {{-- Overview Tab --}}
                <div id="overview-tab" class="tab-content">
                    <div class="prose prose-gray max-w-none">
                        <h3 class="text-xl font-semibold mb-4">About This Course</h3>
                        <div class="text-gray-700 leading-relaxed">
                            {!! nl2br(e($course->description)) !!}
                        </div>

                        @if($course->requirements)
                            <h3 class="text-xl font-semibold mb-4 mt-8">Requirements</h3>
                            <div class="text-gray-700">
                                {!! nl2br(e($course->requirements)) !!}
                            </div>
                        @endif

                        @if($course->what_you_learn)
                            <h3 class="text-xl font-semibold mb-4 mt-8">What You'll Learn</h3>
                            <div class="text-gray-700">
                                {!! nl2br(e($course->what_you_learn)) !!}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Curriculum Tab --}}
                <div id="curriculum-tab" class="tab-content hidden">
                    <h3 class="text-xl font-semibold mb-6">Course Curriculum</h3>
                    @if($course->chapters && $course->chapters->count() > 0)
                        <div class="space-y-4">
                            @foreach($course->chapters as $chapter)
                                <div class="border border-gray-200 rounded-lg">
                                    <div class="p-4 bg-gray-50 border-b">
                                        <h4 class="font-semibold text-gray-900">{{ $chapter->title }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $chapter->lessons->count() }} lessons</p>
                                    </div>
                                    @if($chapter->lessons && $chapter->lessons->count() > 0)
                                        <div class="p-4 space-y-3">
                                            @foreach($chapter->lessons as $lesson)
                                                <div class="flex items-center gap-3 text-sm">
                                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="text-gray-700">{{ $lesson->title }}</span>
                                                    @if($lesson->duration)
                                                        <span class="text-gray-500 text-xs ml-auto">{{ $lesson->duration }} min</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>Curriculum will be available soon.</p>
                        </div>
                    @endif
                </div>

                {{-- Reviews Tab --}}
                <div id="reviews-tab" class="tab-content hidden">
                    <h3 class="text-xl font-semibold mb-6">Student Reviews</h3>
                    <div class="text-center py-8 text-gray-500">
                        <p>Reviews will be available after enrollment.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for tabs and enrollment --}}
<script>
    function showTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Remove active class from all tab buttons
        document.querySelectorAll('.course-tab').forEach(btn => {
            btn.classList.remove('active', 'border-primary', 'text-primary');
            btn.classList.add('border-transparent', 'text-gray-500');
        });

        // Show selected tab
        document.getElementById(tabName + '-tab').classList.remove('hidden');

        // Add active class to selected tab button
        const activeBtn = document.querySelector(`[data-tab="${tabName}"]`);
        activeBtn.classList.add('active', 'border-primary', 'text-primary');
        activeBtn.classList.remove('border-transparent', 'text-gray-500');
    }

    // Enrollment functions are now handled globally in the main dashboard layout
</script>