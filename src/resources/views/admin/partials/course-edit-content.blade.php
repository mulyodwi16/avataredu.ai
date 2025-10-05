{{-- Edit Course Content --}}
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
                <h2 class="text-2xl font-bold text-gray-900">Edit Course</h2>
                <p class="text-gray-600">Update course information</p>
            </div>
        </div>
    </div>

    <form id="editCourseForm" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Course Title --}}
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Course Title *</label>
                <input type="text" name="title" id="course_title" value="{{ $course->title }}"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                    placeholder="Enter course title" required>
                <div class="text-red-500 text-sm mt-1 hidden" id="title_error"></div>
            </div>

            {{-- Category --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                <select name="category_id" id="course_category"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                    required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $course->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <div class="text-red-500 text-sm mt-1 hidden" id="category_error"></div>
            </div>

            {{-- Price --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Price (IDR) *</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                    <input type="number" name="price" id="course_price" value="{{ $course->price }}"
                        class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                        placeholder="0" min="0" required>
                </div>
                <div class="text-red-500 text-sm mt-1 hidden" id="price_error"></div>
                <p class="text-xs text-gray-500 mt-1">Enter 0 for free course</p>
            </div>

            {{-- Level --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Level *</label>
                <select name="level" id="course_level"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                    required>
                    <option value="beginner" {{ $course->level == 'beginner' ? 'selected' : '' }}>Beginner</option>
                    <option value="intermediate" {{ $course->level == 'intermediate' ? 'selected' : '' }}>Intermediate
                    </option>
                    <option value="advanced" {{ $course->level == 'advanced' ? 'selected' : '' }}>Advanced</option>
                </select>
                <div class="text-red-500 text-sm mt-1 hidden" id="level_error"></div>
            </div>

            {{-- Duration --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Duration (Hours) *</label>
                <input type="number" name="duration_hours" id="course_duration" value="{{ $course->duration_hours }}"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                    placeholder="0" min="1" required>
                <div class="text-red-500 text-sm mt-1 hidden" id="duration_error"></div>
            </div>

            {{-- Description --}}
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" id="course_description" rows="4"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                    placeholder="Enter course description" required>{{ $course->description }}</textarea>
                <div class="text-red-500 text-sm mt-1 hidden" id="description_error"></div>
            </div>

            {{-- Current Thumbnail --}}
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Course Thumbnail</label>

                @if($course->thumbnail)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Current thumbnail:</p>
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Current thumbnail"
                            class="w-32 h-20 object-cover rounded-lg border border-gray-200">
                    </div>
                @endif

                <div class="flex items-center justify-center w-full">
                    <label for="course_thumbnail"
                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span
                                    class="font-semibold">{{ $course->thumbnail ? 'Click to change' : 'Click to upload' }}</span>
                                or drag and drop</p>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        </div>
                        <input id="course_thumbnail" name="thumbnail" type="file" class="hidden" accept="image/*" />
                    </label>
                </div>
                <div class="text-red-500 text-sm mt-1 hidden" id="thumbnail_error"></div>
            </div>

            {{-- Publication Status --}}
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Publication Status</label>
                <div class="flex items-center gap-6">
                    <label class="flex items-center">
                        <input type="radio" name="is_published" value="0" {{ !$course->is_published ? 'checked' : '' }}
                            class="mr-2">
                        <span class="text-sm text-gray-700">Draft</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="is_published" value="1" {{ $course->is_published ? 'checked' : '' }}
                            class="mr-2">
                        <span class="text-sm text-gray-700">Published</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Video Upload Options --}}
        <div class="mt-8 space-y-6">
            {{-- Simple Video Upload --}}
            <div class="p-6 bg-green-50 rounded-lg border border-green-200">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Simple Video Upload</h3>
                    <p class="text-gray-600 text-sm">Quick and easy - upload a single main video for this course.</p>
                </div>

                <form id="simpleVideoForm" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    {{-- Current Video Display --}}
                    @if($course->main_video_url)
                        <div class="mb-4 p-4 bg-white rounded-lg border">
                            <p class="text-sm font-medium text-gray-700 mb-2">Current Video:</p>
                            <video controls class="w-full max-w-md h-48 bg-black rounded">
                                <source src="{{ asset('storage/' . $course->main_video_url) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <p class="text-xs text-gray-500 mt-2">Video uploaded on
                                {{ $course->updated_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                    @endif

                    {{-- Video Upload Field --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $course->main_video_url ? 'Replace Video' : 'Upload Video' }} (MP4/MOV)
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="simple_video_file"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-green-300 border-dashed rounded-lg cursor-pointer bg-green-50 hover:bg-green-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500">
                                        <span class="font-semibold">Click to upload video</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500">MP4, MOV up to 100MB</p>
                                </div>
                                <input id="simple_video_file" name="video" type="file" class="hidden"
                                    accept="video/mp4,video/mov" />
                            </label>
                        </div>
                        <div class="text-red-500 text-sm mt-1 hidden" id="simple_video_error"></div>
                    </div>

                    {{-- Video Title --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Video Title</label>
                        <input type="text" name="video_title" id="video_title"
                            value="{{ $course->video_title ?? $course->title }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition"
                            placeholder="Enter video title">
                    </div>

                    {{-- Upload Button --}}
                    <div class="flex gap-3">
                        <button type="button"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2"
                            id="uploadVideoBtn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            {{ $course->main_video_url ? 'Update Video' : 'Upload Video' }}
                        </button>

                        @if($course->main_video_url)
                            <button type="button" onclick="deleteSimpleVideo({{ $course->id }})"
                                class="px-6 py-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                Remove Video
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Advanced Content Management --}}
            <div class="p-6 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Advanced Content Management</h3>
                        <p class="text-gray-600 text-sm">Create structured chapters and lessons with multiple videos.
                        </p>
                        <div class="mt-2 text-sm text-gray-500">
                            Chapters: {{ $course->chapters()->count() }} |
                            Lessons: {{ $course->chapters()->withCount('lessons')->get()->sum('lessons_count') }}
                        </div>
                    </div>
                    <button onclick="loadAdminPage('courses/{{ $course->id }}/content')"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Manage Content
                    </button>
                </div>
            </div>
        </div>

        {{-- Course Statistics --}}
        <div class="mt-8 p-6 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Statistics</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary">{{ $course->enrolled_count ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Students Enrolled</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ number_format($course->average_rating, 1) }}</div>
                    <div class="text-sm text-gray-600">Average Rating</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $course->created_at->format('M d, Y') }}</div>
                    <div class="text-sm text-gray-600">Created Date</div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
            <button type="button" onclick="loadAdminPage('courses')"
                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Cancel
            </button>
            <div class="flex gap-3">
                <button type="button" onclick="deleteCourse({{ $course->id }})"
                    class="px-6 py-3 bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition-colors">
                    Delete Course
                </button>
                <button type="button" onclick="updateCourse({{ $course->id }})"
                    class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                    Update Course
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function updateCourse(courseId) {
        const form = document.getElementById('editCourseForm');
        if (!form) {
            alert('Form not found. Please refresh the page.');
            return;
        }

        // Clear previous errors
        document.querySelectorAll('[id$="_error"]').forEach(el => el.classList.add('hidden'));

        // Validate required fields
        const requiredFields = ['title', 'category_id', 'price', 'level', 'duration_hours', 'description'];
        let hasErrors = false;

        requiredFields.forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            const errorEl = document.getElementById(fieldName + '_error');

            if (field && (!field.value || field.value.trim() === '')) {
                if (errorEl) {
                    errorEl.textContent = 'This field is required.';
                    errorEl.classList.remove('hidden');
                }
                hasErrors = true;
            }
        });

        if (hasErrors) {
            alert('Please fill in all required fields.');
            return;
        }

        // Create FormData properly for file uploads
        const formData = new FormData();

        // Add form fields manually to ensure proper handling
        const formElements = form.querySelectorAll('input, select, textarea');
        formElements.forEach(element => {
            if (element.type === 'file') {
                if (element.files && element.files[0]) {
                    formData.append(element.name, element.files[0]);
                }
            } else if (element.type === 'radio') {
                if (element.checked) {
                    formData.append(element.name, element.value);
                }
            } else if (element.type !== 'submit' && element.type !== 'button') {
                formData.append(element.name, element.value);
            }
        });

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            alert('Security token not found. Please refresh the page.');
            return;
        }

        // Show loading state
        const updateBtn = document.querySelector('button[onclick*="updateCourse"]');
        const originalText = updateBtn ? updateBtn.innerHTML : '';

        if (updateBtn) {
            updateBtn.innerHTML = `
                <svg class="w-4 h-4 animate-spin inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Updating...
            `;
            updateBtn.disabled = true;
        }

        fetch(`/admin/courses/${courseId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-HTTP-Method-Override': 'PUT',
                'Accept': 'application/json'
            }
        })
            .then(response => {
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON:', text);
                        throw new Error('Invalid JSON response: ' + text);
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    alert('Course updated successfully!');
                    loadAdminPage('courses');
                } else {
                    // Show validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorEl = document.getElementById(field + '_error');
                            if (errorEl) {
                                errorEl.textContent = data.errors[field][0];
                                errorEl.classList.remove('hidden');
                            }
                        });
                    } else {
                        alert(data.message || data.error || 'Failed to update course.');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the course: ' + error.message);
            })
            .finally(() => {
                // Restore button state
                if (updateBtn) {
                    updateBtn.innerHTML = originalText;
                    updateBtn.disabled = false;
                }
            });
    }

    function deleteCourse(courseId) {
        if (confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
            fetch(`/admin/courses/${courseId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadAdminPage('courses');
                    } else {
                        alert('Failed to delete course.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the course.');
                });
        }
    }

    // Initialize functions when DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM loaded, initializing upload functions');
        initializeVideoUpload();
        initializeThumbnailUpload();
    });

    // Also initialize immediately in case DOM is already loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initializeVideoUpload();
            initializeThumbnailUpload();
        });
    } else {
        initializeVideoUpload();
        initializeThumbnailUpload();
    }

    function initializeThumbnailUpload() {
        // Initialize thumbnail upload preview
        const thumbnailInput = document.getElementById('course_thumbnail');
        if (thumbnailInput) {
            thumbnailInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                const uploadArea = document.querySelector('label[for="course_thumbnail"]');

                if (file && uploadArea) {
                    // Validate file type and size
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                    const maxSize = 2 * 1024 * 1024; // 2MB

                    if (!allowedTypes.includes(file.type)) {
                        alert('Please select a valid image file (JPG, PNG, GIF)');
                        e.target.value = '';
                        return;
                    }

                    if (file.size > maxSize) {
                        alert('File size must be less than 2MB');
                        e.target.value = '';
                        return;
                    }

                    // Update upload area to show selected file
                    const fileInfo = uploadArea.querySelector('.flex.flex-col.items-center.justify-center');
                    if (fileInfo) {
                        fileInfo.innerHTML = `
                            <svg class="w-8 h-8 mb-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="mb-2 text-sm text-green-600 font-semibold">${file.name}</p>
                            <p class="text-xs text-gray-500">Size: ${(file.size / (1024 * 1024)).toFixed(2)} MB</p>
                            <p class="text-xs text-gray-400 mt-1">Click to change file</p>
                        `;
                    }
                } else if (uploadArea) {
                    // Reset to original state
                    const fileInfo = uploadArea.querySelector('.flex.flex-col.items-center.justify-center');
                    if (fileInfo) {
                        const currentThumbnail = {{ $course->thumbnail ? 'true' : 'false' }};
                        const displayText = currentThumbnail ? 'Click to change' : 'Click to upload';

                        fileInfo.innerHTML = `
                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">${displayText}</span> or drag and drop</p>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        `;
                    }
                }
            });
        }
    }

    function initializeVideoUpload() {
        console.log('Initializing video upload functions');

        // Simple Video Upload Functions - Define in window scope for SPA
        window.uploadSimpleVideo = function (courseId) {
            console.log('Upload function called for course:', courseId);

            const form = document.getElementById('simpleVideoForm');
            const fileInput = document.getElementById('simple_video_file');
            const errorEl = document.getElementById('simple_video_error');
            const uploadBtn = event.target;

            console.log('Form found:', !!form);
            console.log('File input found:', !!fileInput);
            console.log('Error element found:', !!errorEl);

            if (!form || !fileInput || !errorEl) {
                alert('Form elements not found. Please refresh the page.');
                return;
            }

            // Clear previous errors
            errorEl.classList.add('hidden');

            // Validate file
            if (!fileInput.files || fileInput.files.length === 0) {
                console.log('No file selected');
                errorEl.textContent = 'Please select a video file to upload.';
                errorEl.classList.remove('hidden');
                return;
            }

            const file = fileInput.files[0];
            console.log('File selected:', file.name, 'Size:', file.size, 'Type:', file.type);

            const maxSize = 100 * 1024 * 1024; // 100MB

            if (file.size > maxSize) {
                console.log('File too large:', file.size);
                errorEl.textContent = 'File size must be less than 100MB.';
                errorEl.classList.remove('hidden');
                return;
            }

            // Create form data manually to ensure it's correct
            const formData = new FormData();
            formData.append('video', file);
            formData.append('video_title', document.getElementById('video_title')?.value || '');

            console.log('Form data created, starting upload...');

            // Show loading state
            const originalText = uploadBtn.innerHTML;
            uploadBtn.innerHTML = `
            <svg class="w-4 h-4 animate-spin inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Uploading...
        `;
            uploadBtn.disabled = true;

            // Check CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            console.log('CSRF Token found:', !!csrfToken);

            if (!csrfToken) {
                console.error('CSRF token not found');
                errorEl.textContent = 'Security token not found. Please refresh the page.';
                errorEl.classList.remove('hidden');
                uploadBtn.innerHTML = originalText;
                uploadBtn.disabled = false;
                return;
            }

            const uploadUrl = `/admin/courses/${courseId}/upload-video`;
            console.log('Upload URL:', uploadUrl);

            fetch(uploadUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Failed to parse JSON:', text);
                            throw new Error('Invalid JSON response: ' + text);
                        }
                    });
                })
                .then(data => {
                    console.log('Upload response:', data);
                    if (data.success) {
                        alert('Video uploaded successfully!');
                        loadAdminPage(`courses/${courseId}/edit`);
                    } else {
                        if (data.errors) {
                            const firstError = Object.values(data.errors)[0][0];
                            errorEl.textContent = firstError;
                            errorEl.classList.remove('hidden');
                        } else {
                            errorEl.textContent = data.message || data.error || 'Upload failed.';
                            errorEl.classList.remove('hidden');
                        }
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    errorEl.textContent = 'An error occurred while uploading the video: ' + error.message;
                    errorEl.classList.remove('hidden');
                })
                .finally(() => {
                    uploadBtn.innerHTML = originalText;
                    uploadBtn.disabled = false;
                });
        }

        // File selection handler and upload button
        const fileInput = document.getElementById('simple_video_file');
        const uploadBtn = document.getElementById('uploadVideoBtn');

        // Add event listener for upload button
        if (uploadBtn) {
            uploadBtn.addEventListener('click', function () {
                console.log('Upload button clicked via event listener');
                const courseId = {{ $course->id }};
                if (window.uploadSimpleVideo) {
                    window.uploadSimpleVideo(courseId);
                } else {
                    console.error('uploadSimpleVideo function not available');
                }
            });
        }

        if (fileInput) {
            fileInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                const uploadArea = document.querySelector('label[for="simple_video_file"]');

                if (file) {
                    console.log('File selected:', file.name);
                    // Update upload area to show selected file
                    const fileInfo = uploadArea.querySelector('.flex.flex-col.items-center.justify-center');
                    if (fileInfo) {
                        fileInfo.innerHTML = `
                            <svg class="w-8 h-8 mb-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="mb-2 text-sm text-green-600 font-semibold">${file.name}</p>
                            <p class="text-xs text-gray-500">Size: ${(file.size / (1024 * 1024)).toFixed(2)} MB</p>
                            <p class="text-xs text-gray-400 mt-1">Click to change file</p>
                        `;
                    }
                } else {
                    // Reset to original state
                    const fileInfo = uploadArea.querySelector('.flex.flex-col.items-center.justify-center');
                    if (fileInfo) {
                        fileInfo.innerHTML = `
                            <svg class="w-8 h-8 mb-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500">
                                <span class="font-semibold">Click to upload video</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500">MP4, MOV up to 100MB</p>
                        `;
                    }
                }
            });
        }

        window.deleteSimpleVideo = function (courseId) {
            if (confirm('Are you sure you want to remove the video? This action cannot be undone.')) {
                fetch(`/admin/courses/${courseId}/delete-video`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Video removed successfully!');
                            loadAdminPage(`courses/${courseId}/edit`);
                        } else {
                            alert('Failed to remove video.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while removing the video.');
                    });
            }
        }

    } // End initializeVideoUpload function
</script>