{{-- Create Course Content --}}
<div class="bg-gradient-to-br from-white via-blue-50/30 to-slate-50 rounded-xl shadow-sm border border-blue-100/50">
    <div class="p-6 border-b border-blue-200/30 bg-gradient-to-r from-blue-50/30 to-slate-50/30">
        <div class="flex items-center gap-4 mb-4">
            <button onclick="loadAdminPage('courses')"
                class="p-2 rounded-lg bg-gradient-to-r from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 border border-blue-200/50 transition-all duration-200">
                <svg class="w-5 h-5 text-blue-600/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            <div>
                <h2
                    class="text-2xl font-bold bg-gradient-to-r from-blue-800 via-blue-600 to-blue-700 bg-clip-text text-transparent">
                    Create New Course</h2>
                <p class="text-blue-600/80">Add a new course to your catalog</p>
            </div>
        </div>
    </div>

    <form id="createCourseForm" class="p-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Course Title --}}
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Course Title *</label>
                <input type="text" name="title" id="course_title"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-gradient-to-r from-white to-blue-50/30 transition-all duration-200"
                    placeholder="Enter course title" required>
                <div class="text-red-500 text-sm mt-1 hidden" id="title_error"></div>
            </div>

            {{-- Category --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                <select name="category_id" id="course_category"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-gradient-to-r from-white to-blue-50/30 transition-all duration-200"
                    required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <div class="text-red-500 text-sm mt-1 hidden" id="category_error"></div>
            </div>

            {{-- Price --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Price (IDR) *</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                    <input type="number" name="price" id="course_price"
                        class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-gradient-to-r from-white to-blue-50/30 transition-all duration-200"
                        placeholder="0" min="0" required>
                </div>
                <div class="text-red-500 text-sm mt-1 hidden" id="price_error"></div>
                <p class="text-xs text-gray-500 mt-1">Enter 0 for free course</p>
            </div>

            {{-- Level --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Level *</label>
                <select name="level" id="course_level"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-gradient-to-r from-white to-blue-50/30 transition-all duration-200"
                    required>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
                <div class="text-red-500 text-sm mt-1 hidden" id="level_error"></div>
            </div>

            {{-- Duration --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Duration (Hours) *</label>
                <input type="number" name="duration_hours" id="course_duration"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-gradient-to-r from-white to-blue-50/30 transition-all duration-200"
                    placeholder="0" min="1" required>
                <div class="text-red-500 text-sm mt-1 hidden" id="duration_error"></div>
            </div>

            {{-- Description --}}
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" id="course_description" rows="4"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-gradient-to-br from-white to-blue-50/30 transition-all duration-200"
                    placeholder="Enter course description" required></textarea>
                <div class="text-red-500 text-sm mt-1 hidden" id="description_error"></div>
            </div>

            {{-- Thumbnail Upload --}}
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Course Thumbnail</label>
                <div class="flex items-center justify-center w-full">
                    <label for="course_thumbnail"
                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-blue-300/60 border-dashed rounded-lg cursor-pointer bg-gradient-to-br from-blue-50/50 to-slate-50/50 hover:from-blue-100/60 hover:to-slate-100/60 transition-all duration-200">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or
                                drag and drop</p>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        </div>
                        <input id="course_thumbnail" name="thumbnail" type="file" class="hidden" accept="image/*" />
                    </label>
                </div>
                <div class="text-red-500 text-sm mt-1 hidden" id="thumbnail_error"></div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div
            class="flex justify-between items-center mt-8 pt-6 border-t border-blue-200/30 bg-gradient-to-r from-blue-50/20 to-transparent rounded-t-lg">
            <button type="button" onclick="loadAdminPage('courses')"
                class="px-6 py-3 bg-gradient-to-r from-slate-100 to-blue-50 text-slate-600 rounded-lg hover:from-slate-200 hover:to-blue-100 border border-blue-200/30 transition-all duration-200">
                Cancel
            </button>
            <div class="flex gap-3">
                <button type="button" onclick="saveCourse('draft')"
                    class="px-6 py-3 bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-700 rounded-lg hover:from-amber-200 hover:to-yellow-200 border border-amber-200/50 transition-all duration-200 shadow-sm">
                    Save as Draft
                </button>
                <button type="button" onclick="saveCourse('published')"
                    class="px-6 py-3 bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 text-white rounded-lg hover:from-blue-700 hover:via-blue-800 hover:to-blue-900 transition-all duration-200 shadow-lg hover:shadow-blue-500/25 transform hover:scale-[1.02]"
                    style="background: linear-gradient(135deg, rgb(21, 94, 160) 0%, rgb(30, 120, 200) 50%, rgb(15, 80, 140) 100%)">
                    Publish Course
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Initialize functions when DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
        initializeCourseCreate();
    });

    // Also initialize immediately in case DOM is already loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeCourseCreate);
    } else {
        initializeCourseCreate();
    }

    function initializeCourseCreate() {
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
                } else {
                    // Reset to original state
                    const fileInfo = uploadArea.querySelector('.flex.flex-col.items-center.justify-center');
                    if (fileInfo) {
                        fileInfo.innerHTML = `
                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        `;
                    }
                }
            });
        }
    }

    function saveCourse(status) {
        const form = document.getElementById('createCourseForm');
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

        // Add form fields
        const formElements = form.querySelectorAll('input, select, textarea');
        formElements.forEach(element => {
            if (element.type === 'file') {
                if (element.files && element.files[0]) {
                    formData.append(element.name, element.files[0]);
                }
            } else if (element.type !== 'submit' && element.type !== 'button') {
                formData.append(element.name, element.value);
            }
        });

        // Add publication status
        formData.append('is_published', status === 'published' ? '1' : '0');

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            alert('Security token not found. Please refresh the page.');
            return;
        }

        // Show loading state on the clicked button
        const buttons = document.querySelectorAll('button[onclick*="saveCourse"]');
        const clickedButton = event.target;
        const originalText = clickedButton.innerHTML;

        clickedButton.innerHTML = `
            <svg class="w-4 h-4 animate-spin inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            ${status === 'published' ? 'Publishing...' : 'Saving...'}
        `;
        clickedButton.disabled = true;

        // Disable all form buttons during submission
        buttons.forEach(btn => btn.disabled = true);

        fetch('/admin/courses', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
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
                    // Success notification
                    const successMessage = status === 'published' ? 'Course published successfully!' : 'Course saved as draft!';
                    alert(successMessage);
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
                        alert(data.message || data.error || 'Failed to save course.');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the course: ' + error.message);
            })
            .finally(() => {
                // Restore button states
                clickedButton.innerHTML = originalText;
                buttons.forEach(btn => btn.disabled = false);
            });
    }
</script>