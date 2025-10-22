@extends('layouts.app')

@section('title', 'Upload SCORM Course - Admin Dashboard')

@section('content')
    <div class="min-h-screen bg-gray-50">
        {{-- Header --}}
        <header class="bg-white shadow-sm border-b">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center space-x-4">
                        <a href="/admin/dashboard" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Upload SCORM Course</h1>
                            <p class="text-gray-600">Create a new course from a SCORM package</p>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-2xl mx-auto">
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

                {{-- SCORM Upload Form --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <form id="scormUploadForm" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        {{-- SCORM Packages (Multiple) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">SCORM Packages (ZIP) * - Upload Multiple Files for Multi-Chapter Course</label>
                            <div class="space-y-2">
                                <div class="flex items-center justify-center w-full">
                                    <label for="scorm_packages"
                                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-green-300 border-dashed rounded-lg cursor-pointer bg-green-50 hover:bg-green-100 transition">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-10 h-10 mb-3 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                                                </path>
                                            </svg>
                                            <p class="mb-2 text-sm font-semibold text-green-700">
                                                <span>Click to select</span> or drag and drop
                                            </p>
                                            <p class="text-xs text-green-600">Multiple ZIP files (each becomes one chapter)</p>
                                        </div>
                                        <input id="scorm_packages" name="scorm_packages[]" type="file" class="hidden" accept=".zip"
                                            multiple required />
                                    </label>
                                </div>
                                <div id="scorm_error" class="text-red-500 text-sm p-3 bg-red-50 rounded hidden"></div>
                            </div>
                            <div id="scorm_filelist" class="mt-4 hidden">
                                <p class="text-sm font-medium text-gray-700 mb-2">Selected Files: <span id="file_count">0</span></p>
                                <ul id="scorm_files" class="space-y-1 bg-gray-50 p-3 rounded max-h-48 overflow-y-auto"></ul>
                            </div>
                        </div>

                        {{-- Category Selection --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select name="category_id" id="scorm_category" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div id="category_error" class="text-red-500 text-sm mt-2 hidden"></div>
                        </div>

                        {{-- Course Title (Optional) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course Title (Optional)</label>
                            <input type="text" name="title" id="scorm_title"
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition"
                                placeholder="Leave empty to use SCORM title">
                            <p class="text-xs text-gray-500 mt-1">If empty, will use title from SCORM manifest</p>
                        </div>

                        {{-- Price --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price (IDR)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" name="price" id="scorm_price" value="0" min="0"
                                    class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Enter 0 for free course</p>
                        </div>

                        {{-- Course Level --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course Level</label>
                            <select name="level" id="scorm_level"
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition">
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                            </select>
                        </div>

                        {{-- Course Description (Optional) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                            <textarea name="description" id="scorm_description" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition"
                                placeholder="Enter custom description (will override SCORM description)"></textarea>
                            <p class="text-xs text-gray-500 mt-1">If empty, will use default description</p>
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex gap-4 pt-4">
                            <a href="/admin/dashboard"
                                class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-center font-medium">
                                Cancel
                            </a>
                            <button type="submit" id="scormUploadBtn"
                                class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                <span class="upload-text">Upload SCORM Course</span>
                                <span class="loading-text hidden">
                                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Info Box --}}
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h3 class="font-semibold text-blue-900 mb-2">Requirements for SCORM Package:</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>✓ Valid SCORM 1.2 or 2004 package</li>
                        <li>✓ Must contain imsmanifest.xml in root</li>
                        <li>✓ ZIP file (max 100MB)</li>
                        <li>✓ Category selection is required</li>
                    </ul>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Helper to format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // File input change handler for multiple files
        document.getElementById('scorm_packages').addEventListener('change', function (e) {
            const files = e.target.files;
            const filesList = document.getElementById('scorm_files');
            const fileCount = document.getElementById('file_count');
            const errorEl = document.getElementById('scorm_error');
            const filelist = document.getElementById('scorm_filelist');
            
            filesList.innerHTML = '';
            errorEl.classList.add('hidden');
            errorEl.textContent = '';
            
            if (files.length === 0) {
                filelist.classList.add('hidden');
                return;
            }
            
            let totalSize = 0;
            for (let i = 0; i < files.length; i++) {
                totalSize += files[i].size;
                const li = document.createElement('li');
                li.className = 'flex items-center gap-2 text-sm text-gray-700 p-2 bg-white rounded';
                li.innerHTML = `<span class="text-green-600">✓</span> <span class="flex-1 truncate font-medium">${files[i].name}</span> <span class="text-xs text-gray-500 whitespace-nowrap">${formatFileSize(files[i].size)} (Chapter ${i + 1})</span>`;
                filesList.appendChild(li);
            }
            
            fileCount.textContent = files.length;
            filelist.classList.remove('hidden');
            
            // Info message
            errorEl.textContent = `ℹ️ Will upload ${files.length} file(s) (Total: ${formatFileSize(totalSize)}) - this may take a few minutes`;
            errorEl.classList.remove('hidden');
            errorEl.classList.add('text-blue-600', 'bg-blue-50');
        });

        // Form submit handler - upload sequentially
        document.getElementById('scormUploadForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const files = document.getElementById('scorm_packages').files;
            const categoryId = document.getElementById('scorm_category').value;
            const button = document.getElementById('scormUploadBtn');
            const uploadText = button.querySelector('.upload-text');
            const loadingText = button.querySelector('.loading-text');
            const errorEl = document.getElementById('scorm_error');
            const categoryErrorEl = document.getElementById('category_error');

            // Clear previous errors
            errorEl.classList.add('hidden');
            errorEl.textContent = '';
            errorEl.classList.remove('text-blue-600', 'bg-blue-50');
            categoryErrorEl.classList.add('hidden');
            categoryErrorEl.textContent = '';

            // Validate files
            if (files.length === 0) {
                errorEl.textContent = '❌ Please select at least one SCORM package file';
                errorEl.classList.remove('hidden');
                return;
            }

            // Validate category
            if (!categoryId) {
                categoryErrorEl.textContent = '❌ Please select a category';
                categoryErrorEl.classList.remove('hidden');
                return;
            }

            // Prepare form data with all files at once
            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('scorm_packages[]', files[i]);
            }
            formData.append('category_id', categoryId);
            formData.append('title', document.getElementById('scorm_title').value);
            formData.append('price', document.getElementById('scorm_price').value);
            formData.append('level', document.getElementById('scorm_level').value);
            formData.append('description', document.getElementById('scorm_description').value);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            // Show loading
            button.disabled = true;
            uploadText.classList.add('hidden');
            loadingText.classList.remove('hidden');
            
            errorEl.textContent = '⏳ Uploading and processing files... This may take several minutes. Do not close this window.';
            errorEl.classList.remove('hidden');
            errorEl.classList.add('text-blue-600', 'bg-blue-50');

            // Send request
            fetch('{{ route("admin.courses.create-scorm") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.text().then(text => {
                        console.log('Response text (first 500 chars):', text.substring(0, 500));
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('JSON parse error:', e);
                            console.error('Full response:', text);
                            throw new Error(`Server error (status ${response.status}): ${text.substring(0, 300)}`);
                        }
                    });
                })
                .then(data => {
                    if (data.success) {
                        alert(`✓ SCORM course with ${files.length} chapter(s) created successfully!`);
                        window.location.href = data.redirect;
                    } else {
                        errorEl.textContent = `❌ ${data.error || 'Upload failed'}`;
                        errorEl.classList.remove('hidden');
                        errorEl.classList.remove('text-blue-600', 'bg-blue-50');
                        button.disabled = false;
                        uploadText.classList.remove('hidden');
                        loadingText.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorEl.textContent = `❌ ${error.message}`;
                    errorEl.classList.remove('hidden');
                    errorEl.classList.remove('text-blue-600', 'bg-blue-50');
                    button.disabled = false;
                    uploadText.classList.remove('hidden');
                    loadingText.classList.add('hidden');
                });
        });
    </script>
@endsection