@extends('layouts.app')

@section('title', 'SCORM Manager')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">📦 SCORM Manager</h1>
                <p class="text-gray-600">Upload, Debug & Manage SCORM Courses</p>
            </div>

            <!-- Alerts -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <h4 class="font-semibold text-red-900 mb-2">❌ Errors:</h4>
                    <ul class="text-sm text-red-800 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <h4 class="font-semibold text-green-900">✅ {{ session('success') }}</h4>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <h4 class="font-semibold text-red-900">❌ {{ session('error') }}</h4>
                </div>
            @endif

            <!-- Tabs Navigation -->
            <div class="flex gap-4 mb-6 border-b bg-white rounded-t-lg">
                <button onclick="showTab('upload')" class="px-6 py-3 font-semibold border-b-2 border-blue-600 text-blue-600 transition">
                    📤 Upload SCORM
                </button>
                <button onclick="showTab('debug')" class="px-6 py-3 font-semibold border-b-2 border-transparent text-gray-600 hover:text-gray-900 transition">
                    🐛 Debug Info
                </button>
                <button onclick="showTab('courses')" class="px-6 py-3 font-semibold border-b-2 border-transparent text-gray-600 hover:text-gray-900 transition">
                    📚 My Courses
                </button>
            </div>

            <!-- TAB 1: Upload SCORM -->
            <div id="upload" class="tab-content bg-white rounded-b-lg shadow p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Upload New SCORM Package</h2>
                
                <form action="{{ route('admin.scorm.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Course Title -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Course Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="e.g., Interactive Learning Module">
                        @error('title')
                            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Brief description of your course...">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                            <select name="category_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Select Category --</option>
                                @forelse(App\Models\Category::all() as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @empty
                                    <option disabled>No categories available</option>
                                @endforelse
                            </select>
                        </div>

                        <!-- Level -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Level</label>
                            <select name="level"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Price (Rp)</label>
                            <input type="number" name="price" value="{{ old('price', 0) }}" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="0 for free">
                        </div>

                        <!-- Duration -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Duration (hours)</label>
                            <input type="number" name="duration_hours" value="{{ old('duration_hours', 1) }}" min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="1">
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">SCORM Package (ZIP) *</label>
                        <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">
                            <input type="file" id="scormFile" name="scorm_file" accept=".zip" required hidden>
                            <div class="text-4xl mb-2">📦</div>
                            <p class="font-semibold text-gray-700 mb-1">Click to select or drag SCORM file</p>
                            <p class="text-sm text-gray-500">ZIP file containing imsmanifest.xml (Max 500MB)</p>
                            <p id="fileName" class="mt-2 text-sm font-mono text-blue-600"></p>
                        </div>
                        @error('scorm_file')
                            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Requirements -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 mb-2">📋 Requirements:</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>✓ SCORM 1.2 or 2004 format</li>
                            <li>✓ Must contain imsmanifest.xml file</li>
                            <li>✓ ZIP format only</li>
                            <li>✓ Maximum 500MB</li>
                        </ul>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                            ✅ Upload SCORM
                        </button>
                        <button type="reset" class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                            ❌ Reset
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: Debug Info -->
            <div id="debug" class="tab-content hidden bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">🐛 Debug Information</h2>
                
                @php
                    $scormCourses = App\Models\Course::where('content_type', 'scorm')->get();
                    $scormStoragePath = storage_path('app/scorm');
                    $scormDirExists = is_dir($scormStoragePath);
                    $scormPackages = $scormDirExists ? array_diff(scandir($scormStoragePath), ['.', '..', '.gitignore']) : [];
                @endphp

                <!-- Database Info -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Database SCORM Courses:</h3>
                    
                    @if($scormCourses->isEmpty())
                        <div class="bg-yellow-50 border border-yellow-200 rounded p-4 text-yellow-800">
                            ⚠️ No SCORM courses in database
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs border-collapse">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border border-gray-300 px-3 py-2 text-left">ID</th>
                                        <th class="border border-gray-300 px-3 py-2 text-left">Title</th>
                                        <th class="border border-gray-300 px-3 py-2 text-left">Package Path</th>
                                        <th class="border border-gray-300 px-3 py-2 text-left">Entry Point</th>
                                        <th class="border border-gray-300 px-3 py-2 text-left">Version</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach($scormCourses as $course)
                                        <tr class="hover:bg-gray-50">
                                            <td class="border border-gray-300 px-3 py-2">{{ $course->id }}</td>
                                            <td class="border border-gray-300 px-3 py-2 font-medium">{{ $course->title }}</td>
                                            <td class="border border-gray-300 px-3 py-2 font-mono">{{ $course->scorm_package_path ?? '❌ NULL' }}</td>
                                            <td class="border border-gray-300 px-3 py-2 font-mono">{{ $course->scorm_entry_point ?? '❌ NULL' }}</td>
                                            <td class="border border-gray-300 px-3 py-2">{{ $course->scorm_version ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Storage Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 mb-3">📁 Storage System:</h3>
                    <div class="space-y-2 text-sm">
                        <div>
                            <strong>Path:</strong> {{ $scormStoragePath }}
                        </div>
                        <div>
                            <strong>Exists:</strong> 
                            @if($scormDirExists)
                                <span class="text-green-600">✅ Yes</span>
                            @else
                                <span class="text-red-600">❌ No</span>
                            @endif
                        </div>
                        @if($scormDirExists)
                            <div>
                                <strong>Packages Found:</strong> {{ count($scormPackages) }}
                                @if(!empty($scormPackages))
                                    <ul class="mt-2 space-y-1">
                                        @foreach($scormPackages as $pkg)
                                            <li class="text-xs bg-white p-2 rounded border font-mono">📦 {{ $pkg }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- TAB 3: Courses List -->
            <div id="courses" class="tab-content hidden bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">📚 My SCORM Courses</h2>
                
                @php
                    $myCourses = Auth::user()->courses()->where('content_type', 'scorm')->get();
                @endphp

                @if($myCourses->isEmpty())
                    <div class="bg-gray-50 rounded-lg p-8 text-center">
                        <p class="text-gray-600 mb-4">No SCORM courses yet</p>
                        <button onclick="showTab('upload')" class="text-blue-600 hover:underline font-semibold">
                            Create your first SCORM course →
                        </button>
                    </div>
                @else
                    <div class="grid gap-4">
                        @foreach($myCourses as $course)
                            <div class="border rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $course->title }}</h3>
                                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $course->description }}</p>
                                        <div class="flex gap-4 mt-3 text-xs text-gray-500 flex-wrap">
                                            <span>🆔 ID: {{ $course->id }}</span>
                                            <span>📦 {{ $course->scorm_version ?? 'SCORM' }}</span>
                                            <span>💰 Rp {{ number_format((int)$course->price) }}</span>
                                            <span>⏱️ {{ $course->duration_hours }} hours</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('courses.learn', $course->id) }}" target="_blank"
                                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition whitespace-nowrap">
                                            👁️ Preview
                                        </a>
                                        <button onclick="deleteCourse({{ $course->id }})"
                                            class="px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition whitespace-nowrap">
                                            🗑️ Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Tab switching
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            document.getElementById(tabName).classList.remove('hidden');
        }

        // File upload handling
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('scormFile');
        const fileName = document.getElementById('fileName');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            let dt = e.dataTransfer;
            let files = dt.files;
            fileInput.files = files;
            updateFileName();
        }

        dropZone.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', updateFileName);

        function updateFileName() {
            if (fileInput.files.length > 0) {
                fileName.textContent = '✅ ' + fileInput.files[0].name;
            } else {
                fileName.textContent = '';
            }
        }

        // Delete course
        function deleteCourse(courseId) {
            if (!confirm('Are you sure? This cannot be undone.')) return;

            fetch(`/admin/api/courses/${courseId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Course deleted');
                    location.reload();
                } else {
                    alert('❌ Error: ' + (data.message || 'Failed'));
                }
            })
            .catch(e => alert('❌ Error: ' + e.message));
        }

        // Show upload tab on page load
        showTab('upload');
    </script>
@endsection
