{{-- Course Content Management --}}
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center gap-4 mb-4">
            <button onclick="loadAdminPage('courses')"
                class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Manage Course Content</h2>
                <p class="text-gray-600">{{ $course->title }}</p>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="text-sm text-gray-500">
                Course ID: {{ $course->id }} |
                Chapters: {{ $chapters->count() }} |
                Lessons: {{ $chapters->sum(function ($chapter) {
    return $chapter->lessons->count(); }) }}
            </div>
            <button onclick="showAddChapterModal()"
                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Chapter
            </button>
        </div>
    </div>

    <div class="p-6">
        @if($chapters->count() > 0)
            <div class="space-y-6">
                @foreach($chapters as $chapter)
                    <div class="border border-gray-200 rounded-lg overflow-hidden" id="chapter-{{ $chapter->id }}">
                        {{-- Chapter Header --}}
                        <div class="bg-gray-50 p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-primary/10 text-primary rounded-full flex items-center justify-center text-sm font-semibold">
                                        {{ $chapter->order }}
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $chapter->title }}</h3>
                                        @if($chapter->description)
                                            <p class="text-sm text-gray-600">{{ $chapter->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                        {{ $chapter->lessons->count() }} lessons
                                    </span>
                                    <button onclick="showAddLessonModal({{ $chapter->id }})"
                                        class="p-2 text-gray-600 hover:text-primary hover:bg-primary/10 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                    <button onclick="editChapter({{ $chapter->id }})"
                                        class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button onclick="deleteChapter({{ $chapter->id }})"
                                        class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Lessons List --}}
                        @if($chapter->lessons->count() > 0)
                            <div class="p-4">
                                <div class="space-y-3">
                                    @foreach($chapter->lessons->sortBy('order') as $lesson)
                                        <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg" id="lesson-{{ $lesson->id }}">
                                            <div
                                                class="w-6 h-6 bg-white border-2 border-gray-300 rounded-full flex items-center justify-center text-xs font-medium">
                                                {{ $lesson->order }}
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-900">{{ $lesson->title }}</div>
                                                <div class="text-sm text-gray-500 flex items-center gap-4">
                                                    @if($lesson->duration)
                                                        <span>{{ gmdate('H:i:s', $lesson->duration) }}</span>
                                                    @endif
                                                    @if($lesson->video_url)
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m6-4a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Video
                                                        </span>
                                                    @else
                                                        <span class="flex items-center gap-1 text-amber-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            No Video
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button onclick="editLesson({{ $lesson->id }})"
                                                    class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                <button onclick="deleteLesson({{ $lesson->id }})"
                                                    class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="p-8 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <p>No lessons in this chapter yet.</p>
                                <button onclick="showAddLessonModal({{ $chapter->id }})"
                                    class="mt-2 text-primary hover:text-primary/80">
                                    Add your first lesson
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No chapters yet</h3>
                <p class="text-gray-600 mb-6">Start organizing your course by creating chapters and lessons.</p>
                <button onclick="showAddChapterModal()"
                    class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                    Create First Chapter
                </button>
            </div>
        @endif
    </div>
</div>

{{-- Add Chapter Modal --}}
<div id="addChapterModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New Chapter</h3>
        <form id="addChapterForm" onsubmit="submitAddChapter(event)">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chapter Title *</label>
                    <input type="text" name="title" id="chapter_title" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                        placeholder="Enter chapter title">
                    <div class="text-red-500 text-sm mt-1 hidden" id="chapter_title_error"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="chapter_description" rows="3"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                        placeholder="Enter chapter description (optional)"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order *</label>
                    <input type="number" name="order" id="chapter_order" required min="1"
                        value="{{ $chapters->count() + 1 }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                        placeholder="Chapter order">
                    <div class="text-red-500 text-sm mt-1 hidden" id="chapter_order_error"></div>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="hideAddChapterModal()"
                    class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                    Add Chapter
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Add Lesson Modal --}}
<div id="addLessonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-2xl mx-4 max-h-screen overflow-y-auto">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New Lesson</h3>
        <form id="addLessonForm" onsubmit="submitAddLesson(event)" enctype="multipart/form-data">
            <input type="hidden" id="lesson_chapter_id" name="chapter_id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lesson Title *</label>
                    <input type="text" name="title" id="lesson_title" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                        placeholder="Enter lesson title">
                    <div class="text-red-500 text-sm mt-1 hidden" id="lesson_title_error"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                    <textarea name="content" id="lesson_content" rows="4"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                        placeholder="Enter lesson content/description"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order *</label>
                        <input type="number" name="order" id="lesson_order" required min="1"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                            placeholder="Lesson order">
                        <div class="text-red-500 text-sm mt-1 hidden" id="lesson_order_error"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                        <input type="number" name="duration" id="lesson_duration" min="1"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                            placeholder="Lesson duration">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Video Upload</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="lesson_video"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500">
                                    <span class="font-semibold">Click to upload video</span> or drag and drop
                                </p>
                                <p class="text-xs text-gray-500">MP4, AVI, MOV up to 100MB</p>
                            </div>
                            <input id="lesson_video" name="video" type="file" class="hidden" accept="video/*" />
                        </label>
                    </div>
                    <div class="text-red-500 text-sm mt-1 hidden" id="lesson_video_error"></div>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="hideAddLessonModal()"
                    class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                    Add Lesson
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Chapter Management Functions - Make them globally available
    window.showAddChapterModal = function () {
        console.log('showAddChapterModal called');
        const modal = document.getElementById('addChapterModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            console.error('addChapterModal element not found');
        }
    }

    window.hideAddChapterModal = function () {
        const modal = document.getElementById('addChapterModal');
        const form = document.getElementById('addChapterForm');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        if (form) {
            form.reset();
        }
    }

    window.submitAddChapter = function (event) {
        event.preventDefault();
        console.log('submitAddChapter called');

        const formData = new FormData(event.target);

        fetch(`/admin/courses/{{ $course->id }}/chapters`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                console.log('Chapter creation response:', data);
                if (data.success) {
                    window.hideAddChapterModal();
                    if (window.loadAdminPage) {
                        window.loadAdminPage('courses/{{ $course->id }}/content');
                    }
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorEl = document.getElementById('chapter_' + field + '_error');
                            if (errorEl) {
                                errorEl.textContent = data.errors[field][0];
                                errorEl.classList.remove('hidden');
                            }
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the chapter.');
            });
    }

    // Lesson Management Functions
    window.showAddLessonModal = function (chapterId) {
        console.log('showAddLessonModal called for chapter:', chapterId);

        const chapterInput = document.getElementById('lesson_chapter_id');
        const orderInput = document.getElementById('lesson_order');
        const modal = document.getElementById('addLessonModal');

        if (chapterInput) chapterInput.value = chapterId;

        // Set next lesson order
        const chapterElement = document.getElementById(`chapter-${chapterId}`);
        if (chapterElement && orderInput) {
            const lessonCount = chapterElement.querySelectorAll('[id^="lesson-"]').length;
            orderInput.value = lessonCount + 1;
        }

        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    window.hideAddLessonModal = function () {
        const modal = document.getElementById('addLessonModal');
        const form = document.getElementById('addLessonForm');

        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        if (form) {
            form.reset();
        }
    }

    window.submitAddLesson = function (event) {
        event.preventDefault();
        console.log('submitAddLesson called');

        const formData = new FormData(event.target);
        const chapterId = document.getElementById('lesson_chapter_id').value;

        fetch(`/admin/courses/{{ $course->id }}/chapters/${chapterId}/lessons`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                console.log('Lesson creation response:', data);
                if (data.success) {
                    window.hideAddLessonModal();
                    if (window.loadAdminPage) {
                        window.loadAdminPage('courses/{{ $course->id }}/content');
                    }
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorEl = document.getElementById('lesson_' + field + '_error');
                            if (errorEl) {
                                errorEl.textContent = data.errors[field][0];
                                errorEl.classList.remove('hidden');
                            }
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the lesson.');
            });
    }

    // Edit Functions (placeholder - will implement modal editing later)
    window.editChapter = function (chapterId) {
        alert('Edit chapter functionality will be implemented in the next iteration.');
    }

    window.editLesson = function (lessonId) {
        alert('Edit lesson functionality will be implemented in the next iteration.');
    }

    // Delete Functions
    window.deleteChapter = function (chapterId) {
        if (confirm('Are you sure you want to delete this chapter and all its lessons? This action cannot be undone.')) {
            fetch(`/admin/courses/{{ $course->id }}/chapters/${chapterId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (window.loadAdminPage) {
                            window.loadAdminPage('courses/{{ $course->id }}/content');
                        }
                    } else {
                        alert('Failed to delete chapter.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the chapter.');
                });
        }
    }

    window.deleteLesson = function (lessonId) {
        if (confirm('Are you sure you want to delete this lesson? This action cannot be undone.')) {
            fetch(`/admin/courses/{{ $course->id }}/lessons/${lessonId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (window.loadAdminPage) {
                            window.loadAdminPage('courses/{{ $course->id }}/content');
                        }
                    } else {
                        alert('Failed to delete lesson.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the lesson.');
                });
        }
    }

        // Clear previous errors on input - Use immediate execution since DOM is already loaded
        (function () {
            setTimeout(() => {
                const inputs = document.querySelectorAll('#addChapterForm input, #addChapterForm textarea, #addLessonForm input, #addLessonForm textarea');
                inputs.forEach(input => {
                    input.addEventListener('input', function () {
                        const errorId = this.id + '_error';
                        const errorEl = document.getElementById(errorId);
                        if (errorEl) {
                            errorEl.classList.add('hidden');
                        }
                    });
                });
            }, 100); // Small delay to ensure DOM elements are available
        })();

    // Log that functions are loaded
    console.log('Course content management functions loaded:', {
        showAddChapterModal: typeof window.showAddChapterModal,
        hideAddChapterModal: typeof window.hideAddChapterModal,
        submitAddChapter: typeof window.submitAddChapter,
        showAddLessonModal: typeof window.showAddLessonModal,
        hideAddLessonModal: typeof window.hideAddLessonModal,
        submitAddLesson: typeof window.submitAddLesson
    });
</script>