@extends('layouts.app')

@section('title', 'Edit Course - Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <header class="bg-white shadow-sm border-b">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <a href="/admin/dashboard" 
                        class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Edit Course</h1>
                        <p class="text-gray-600">Update course: {{ $course->title }}</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-6xl mx-auto">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Course Edit Form --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form id="editCourseForm" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Basic Information Section --}}
                    <div class="space-y-6">
                        {{-- Course Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course Title *</label>
                            <input type="text" name="title" id="course_title" value="{{ $course->title }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                                placeholder="Enter course title" required>
                            <div class="text-red-500 text-sm mt-1 hidden" id="title_error"></div>
                        </div>

                        {{-- Category & Level (Side by side) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Level *</label>
                                <select name="level" id="course_level"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                                    required>
                                    <option value="beginner" {{ $course->level == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ $course->level == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ $course->level == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                </select>
                                <div class="text-red-500 text-sm mt-1 hidden" id="level_error"></div>
                            </div>
                        </div>

                        {{-- Price & Duration (Side by side) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Duration (Hours) *</label>
                                <input type="number" name="duration_hours" id="course_duration" value="{{ $course->duration_hours }}"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                                    placeholder="0" min="1" required>
                                <div class="text-red-500 text-sm mt-1 hidden" id="duration_error"></div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                            <textarea name="description" id="course_description" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                                placeholder="Enter course description" required>{{ $course->description }}</textarea>
                            <div class="text-red-500 text-sm mt-1 hidden" id="description_error"></div>
                        </div>
                    </div>

                    {{-- Thumbnail Section --}}
                    <div class="space-y-4 border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-semibold text-gray-900">Course Thumbnail</h3>

                        @if($course->thumbnail)
                            <div>
                                <p class="text-sm text-gray-600 mb-3">Current thumbnail:</p>
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Current thumbnail"
                                    class="w-32 h-20 object-cover rounded-lg border border-gray-200">
                            </div>
                        @endif

                        <div class="flex items-center justify-center w-full">
                            <label for="course_thumbnail"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
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
                    <div class="space-y-3 border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-semibold text-gray-900">Publication Status</h3>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="is_published" value="0" {{ !$course->is_published ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary focus:ring-2 focus:ring-primary mr-2">
                                <span class="text-sm text-gray-700">Draft</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="is_published" value="1" {{ $course->is_published ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary focus:ring-2 focus:ring-primary mr-2">
                                <span class="text-sm text-gray-700">Published</span>
                            </label>
                        </div>
                    </div>

                    {{-- SCORM Content Section --}}
                    @if($course->content_type === 'scorm')
                        <div class="space-y-4 border-t border-gray-200 pt-6">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                                </svg>
                                <h3 class="text-sm font-semibold text-gray-900">SCORM Package</h3>
                                <span class="inline-block px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded-full">SCORM</span>
                            </div>
                            <p class="text-gray-600 text-sm">This course uses SCORM (Sharable Content Object Reference Model) content package.</p>

                            <div class="space-y-3 mt-4">
                                {{-- Package Info --}}
                                @if($course->scorm_package_path)
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 mb-2">Package Location:</p>
                                        <p class="text-xs font-mono text-gray-600 bg-gray-100 p-3 rounded break-all border border-gray-200">
                                            {{ $course->scorm_package_path }}
                                        </p>
                                    </div>
                                @endif

                                @if($course->scorm_version)
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 mb-1">SCORM Version:</p>
                                        <p class="text-sm text-gray-600">{{ $course->scorm_version }}</p>
                                    </div>
                                @endif

                                <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <p class="text-sm text-blue-800">
                                        <strong>Note:</strong> SCORM content is loaded from the package file. To replace it, delete and re-upload the course.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Video Upload Section --}}
                        <div class="space-y-6 border-t border-gray-200 pt-6">
                            <h3 class="text-sm font-semibold text-gray-900">Video Content</h3>

                            <form id="simpleVideoForm" enctype="multipart/form-data" class="space-y-6">
                                @csrf

                                {{-- Current Video Display --}}
                                @if($course->main_video_url)
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 mb-3">Current Video:</p>
                                        <video controls class="w-full max-w-md h-48 bg-black rounded-lg border border-gray-200">
                                            <source src="{{ asset('storage/' . $course->main_video_url) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                        <p class="text-xs text-gray-500 mt-2">Last updated: {{ $course->updated_at->format('M d, Y H:i') }}</p>
                                    </div>
                                @endif

                                {{-- Video Upload Field --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        {{ $course->main_video_url ? 'Replace Video' : 'Upload Video' }} (MP4/MOV)
                                    </label>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="simple_video_file"
                                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-green-300 border-dashed rounded-lg cursor-pointer bg-green-50 hover:bg-green-100 transition">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-4 text-green-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500">
                                                    <span class="font-semibold">Click to select</span> or drag and drop
                                                </p>
                                                <p class="text-xs text-green-600">MP4, MOV up to 100MB</p>
                                            </div>
                                            <input id="simple_video_file" name="video" type="file" class="hidden"
                                                accept="video/mp4,video/mov" />
                                        </label>
                                    </div>
                                    <div class="text-red-500 text-sm mt-2 hidden" id="simple_video_error"></div>
                                </div>

                                {{-- Video Title --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Video Title</label>
                                    <input type="text" name="video_title" id="video_title"
                                        value="{{ $course->video_title ?? $course->title }}"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition"
                                        placeholder="Enter video title">
                                </div>

                                {{-- Upload/Remove Buttons --}}
                                <div class="flex gap-3 pt-4 border-t border-gray-200">
                                    <button type="button"
                                        class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2"
                                        id="uploadVideoBtn">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        {{ $course->main_video_url ? 'Update Video' : 'Upload Video' }}
                                    </button>

                                    @if($course->main_video_url)
                                        <button type="button" onclick="deleteSimpleVideo({{ $course->id }})"
                                            class="flex-1 px-6 py-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                            Remove Video
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    @endif

                    {{-- Course Statistics --}}
                    <div class="space-y-4 border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-semibold text-gray-900">Course Statistics</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-center">
                                <div class="text-2xl font-bold text-primary">{{ $course->enrolled_count ?? 0 }}</div>
                                <div class="text-sm text-gray-600 mt-1">Students Enrolled</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-center">
                                <div class="text-2xl font-bold text-green-600">{{ number_format($course->average_rating, 1) }}</div>
                                <div class="text-sm text-gray-600 mt-1">Average Rating</div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $course->created_at->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-600 mt-1">Created Date</div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-6">
                        <a href="/admin/dashboard"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                            Cancel
                        </a>
                        <div class="flex gap-3">
                            <button type="button" onclick="deleteCourse({{ $course->id }})"
                                class="px-6 py-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors font-medium">
                                Delete Course
                            </button>
                            <button type="button" onclick="updateCourse({{ $course->id }})"
                                class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-medium">
                                Update Course
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
    // Initialize functions when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeVideoUpload();
        initializeThumbnailUpload();
    });

    function initializeThumbnailUpload() {
        const thumbnailInput = document.getElementById('course_thumbnail');
        if (thumbnailInput) {
            thumbnailInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const uploadArea = document.querySelector('label[for="course_thumbnail"]');
                
                if (file && uploadArea) {
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
                }
            });
        }
    }

    function initializeVideoUpload() {
        const uploadBtn = document.getElementById('uploadVideoBtn');
        if (uploadBtn) {
            uploadBtn.addEventListener('click', function() {
                uploadSimpleVideo({{ $course->id }});
            });
        }

        const fileInput = document.getElementById('simple_video_file');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const uploadArea = document.querySelector('label[for="simple_video_file"]');

                if (file && uploadArea) {
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
                }
            });
        }
    }

    function updateCourse(courseId) {
        const form = document.getElementById('editCourseForm');
        if (!form) {
            alert('Form not found. Please refresh the page.');
            return;
        }

        console.log('=== Starting Form Validation ===');
        document.querySelectorAll('[id$="_error"]').forEach(el => el.classList.add('hidden'));

        const requiredFields = ['title', 'category_id', 'price', 'level', 'duration_hours', 'description'];
        let hasErrors = false;
        const fieldValues = {};

        for (const fieldName of requiredFields) {
            const field = form.querySelector(`[name="${fieldName}"]`);
            console.log(`Checking field "${fieldName}":`, field);
            
            if (!field) {
                console.warn(`Field "${fieldName}" not found in form`);
                continue;
            }

            // Get value based on field type
            let value = '';
            if (field.type === 'select-one' || field.type === 'select-multiple') {
                value = field.value ? String(field.value).trim() : '';
            } else if (field.type === 'number') {
                value = field.value !== undefined ? String(field.value).trim() : '';
            } else {
                value = field.value ? String(field.value).trim() : '';
            }

            fieldValues[fieldName] = value;
            console.log(`Field "${fieldName}" value:`, {
                type: field.type,
                value: value,
                length: value.length,
                isEmpty: !value
            });

            // Check if field is truly empty
            const isEmpty = !value || value === '0' || value === '';
            
            // Special handling for different field types
            if (fieldName === 'price') {
                // Price can be 0 for free courses, just needs to be a valid number
                if (isEmpty && value !== '0') {
                    const errorEl = document.getElementById(fieldName + '_error');
                    if (errorEl) {
                        errorEl.textContent = 'Price is required.';
                        errorEl.classList.remove('hidden');
                    }
                    hasErrors = true;
                } else {
                    const numValue = parseFloat(value);
                    if (isNaN(numValue)) {
                        const errorEl = document.getElementById(fieldName + '_error');
                        if (errorEl) {
                            errorEl.textContent = 'Price must be a valid number.';
                            errorEl.classList.remove('hidden');
                        }
                        hasErrors = true;
                    }
                }
            } else if (fieldName === 'duration_hours') {
                // Duration must be >= 1
                const numValue = parseInt(value);
                if (isNaN(numValue) || numValue < 1) {
                    const errorEl = document.getElementById(fieldName + '_error');
                    if (errorEl) {
                        errorEl.textContent = 'Duration must be at least 1 hour.';
                        errorEl.classList.remove('hidden');
                    }
                    hasErrors = true;
                }
            } else {
                // All other required fields cannot be empty
                if (isEmpty) {
                    const errorEl = document.getElementById(fieldName + '_error');
                    if (errorEl) {
                        errorEl.textContent = 'This field is required.';
                        errorEl.classList.remove('hidden');
                    }
                    hasErrors = true;
                    console.warn(`Validation failed for "${fieldName}": field is empty`);
                }
            }
        }

        console.log('=== Validation Complete ===');
        console.log('Collected field values:', fieldValues);
        console.log('Has validation errors:', hasErrors);
        
        if (hasErrors) {
            console.error('Validation failed. Errors shown above.');
            return;
        }

        // Build FormData, including _token for CSRF protection
        const formData = new FormData(form);
        
        console.log('=== FormData Ready to Submit ===');
        console.log('FormData entries:', Array.from(formData.entries()).map(([k, v]) => [k, v instanceof File ? `[File: ${v.name}]` : v]));

        // Get CSRF token
        const csrfToken = document.querySelector('input[name="_token"]')?.value;
        if (!csrfToken) {
            console.error('CSRF token not found!');
            alert('Security error: CSRF token not found. Please refresh the page and try again.');
            return;
        }
        
        // Ensure CSRF token is in FormData
        if (!formData.has('_token')) {
            formData.append('_token', csrfToken);
        }
        if (!formData.has('_method')) {
            formData.append('_method', 'PUT');
        }
        
        console.log('CSRF token included:', csrfToken.substring(0, 10) + '...');

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

        console.log('Updating course:', courseId);
        console.log('Endpoint: /admin/courses/' + courseId);

        // Since FormData includes _token and _method, just send it directly
        // FormData will be sent with multipart/form-data content type automatically
        fetch(`/admin/courses/${courseId}`, {
            method: 'POST', // Must be POST because we're using _method field
            body: formData
            // Don't specify Content-Type - let the browser set it automatically for FormData
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.text().then(text => {
                console.log('Raw response text:', text.substring(0, 200));
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Failed to parse JSON:', text);
                    throw new Error('Invalid JSON response: ' + text.substring(0, 500));
                }
            });
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert('Course updated successfully!');
                window.location.href = "/admin/dashboard";
            } else {
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
            if (updateBtn) {
                updateBtn.innerHTML = originalText;
                updateBtn.disabled = false;
            }
        });
    }

    function uploadSimpleVideo(courseId) {
        const form = document.getElementById('simpleVideoForm');
        const fileInput = document.getElementById('simple_video_file');
        const errorEl = document.getElementById('simple_video_error');
        const uploadBtn = document.getElementById('uploadVideoBtn');

        if (!form || !fileInput || !errorEl) {
            console.error('Form elements not found');
            alert('Form elements not found. Please refresh the page.');
            return;
        }

        errorEl.classList.add('hidden');

        if (!fileInput.files || fileInput.files.length === 0) {
            errorEl.textContent = 'Please select a video file to upload.';
            errorEl.classList.remove('hidden');
            return;
        }

        const file = fileInput.files[0];
        const maxSize = 100 * 1024 * 1024; // 100MB

        if (file.size > maxSize) {
            errorEl.textContent = 'File size must be less than 100MB.';
            errorEl.classList.remove('hidden');
            return;
        }

        const formData = new FormData();
        formData.append('video', file);
        formData.append('video_title', document.getElementById('video_title')?.value || '');

        const originalText = uploadBtn.innerHTML;
        uploadBtn.innerHTML = `
            <svg class="w-4 h-4 animate-spin inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Uploading...
        `;
        uploadBtn.disabled = true;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        console.log('Uploading video for course:', courseId);
        console.log('File:', file.name, 'Size:', file.size);

        fetch(`/admin/courses/${courseId}/upload-video`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Upload response status:', response.status);
            return response.text().then(text => {
                console.log('Upload response text:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Failed to parse upload JSON:', text);
                    throw new Error('Invalid JSON response: ' + text);
                }
            });
        })
        .then(data => {
            console.log('Upload response data:', data);
            if (data.success) {
                alert('Video uploaded successfully!');
                location.reload();
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

    function deleteSimpleVideo(courseId) {
        if (confirm('Are you sure you want to remove the video? This action cannot be undone.')) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            console.log('Deleting video for course:', courseId);

            fetch(`/admin/courses/${courseId}/delete-video`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Delete response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Delete response data:', data);
                if (data.success) {
                    alert('Video removed successfully!');
                    location.reload();
                } else {
                    alert(data.error || 'Failed to remove video.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while removing the video.');
            });
        }
    }

    function deleteCourse(courseId) {
        if (confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            console.log('Deleting course:', courseId);

            fetch(`/admin/courses/${courseId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Delete course response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Delete course response data:', data);
                if (data.success) {
                    alert('Course deleted successfully!');
                    window.location.href = "/admin/dashboard";
                } else {
                    alert(data.error || 'Failed to delete course.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the course.');
            });
        }
    }
</script>
@endsection