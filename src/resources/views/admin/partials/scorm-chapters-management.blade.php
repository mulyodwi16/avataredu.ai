{{-- SCORM Chapters Management --}}
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex justify-between items-center">
        <h3 class="text-xl font-bold text-gray-900">SCORM Chapters</h3>
        <button type="button" onclick="showAddScormChapterModal()"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            + Add Chapter
        </button>
    </div>

    {{-- SCORM Chapters List --}}
    <div id="scormChaptersList" class="bg-white rounded-lg border border-gray-200 divide-y divide-gray-200">
        <!-- Chapters will be loaded here -->
    </div>
</div>

{{-- Add/Edit SCORM Chapter Modal --}}
<div id="addScormChapterModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold mb-4">Add SCORM Chapter</h3>

        <form id="scormChapterForm" class="space-y-4">
            {{-- Chapter Title --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Chapter Title *</label>
                <input type="text" id="scormChapterTitle" name="title"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g., Chapter 1: Introduction" required>
                <div id="scormChapterTitle_error" class="text-sm text-red-600 mt-1 hidden"></div>
            </div>

            {{-- Chapter Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="scormChapterDescription" name="description"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 rows-3"
                    placeholder="Chapter description..."></textarea>
            </div>

            {{-- SCORM Package Upload --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">SCORM Package (.zip) *</label>
                <input type="file" id="scormChapterFile" name="scorm_file" accept=".zip"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                <p class="text-xs text-gray-500 mt-1">Upload a SCORM 1.2 or 2004 package</p>
                <div id="scormChapterFile_error" class="text-sm text-red-600 mt-1 hidden"></div>
            </div>

            {{-- Duration --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Duration (Minutes)</label>
                <input type="number" id="scormChapterDuration" name="duration_minutes"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    min="0" placeholder="0">
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 justify-end pt-4 border-t">
                <button type="button" onclick="closeAddScormChapterModal()"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Add Chapter
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentCourseId = {{ $course->id }};

    // Load SCORM chapters on page load
    document.addEventListener('DOMContentLoaded', function () {
        loadScormChapters();
    });

    function loadScormChapters() {
        fetch(`/admin/courses/${currentCourseId}/scorm-chapters`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('scormChaptersList');
                if (data.chapters.length === 0) {
                    container.innerHTML = '<div class="p-6 text-center text-gray-500">No SCORM chapters yet. Upload one to get started.</div>';
                    return;
                }

                container.innerHTML = data.chapters.map((chapter, index) => `
                <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">Chapter ${chapter.order}</span>
                            <h4 class="font-semibold text-gray-900">${chapter.title}</h4>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">${chapter.description || 'No description'}</p>
                        <div class="flex gap-4 mt-2 text-xs text-gray-500">
                            <span>📦 SCORM ${chapter.scorm_version}</span>
                            <span>⏱️ ${chapter.duration_minutes} min</span>
                            <span>${chapter.is_published ? '✓ Published' : '✗ Draft'}</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="editScormChapter(${chapter.id})" class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                            Edit
                        </button>
                        <button type="button" onclick="deleteScormChapter(${chapter.id})" class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200 transition">
                            Delete
                        </button>
                    </div>
                </div>
            `).join('');
            })
            .catch(error => console.error('Error loading SCORM chapters:', error));
    }

    function showAddScormChapterModal() {
        document.getElementById('addScormChapterModal').classList.remove('hidden');
        document.getElementById('scormChapterForm').reset();
    }

    function closeAddScormChapterModal() {
        document.getElementById('addScormChapterModal').classList.add('hidden');
    }

    document.getElementById('scormChapterForm').addEventListener('submit', function (e) {
        e.preventDefault();

        // Validation
        const title = document.getElementById('scormChapterTitle').value.trim();
        const file = document.getElementById('scormChapterFile').files[0];

        if (!title) {
            document.getElementById('scormChapterTitle_error').textContent = 'Title is required';
            document.getElementById('scormChapterTitle_error').classList.remove('hidden');
            return;
        }

        if (!file) {
            document.getElementById('scormChapterFile_error').textContent = 'Please select a SCORM package';
            document.getElementById('scormChapterFile_error').classList.remove('hidden');
            return;
        }

        // Build FormData
        const formData = new FormData();
        formData.append('title', title);
        formData.append('description', document.getElementById('scormChapterDescription').value);
        formData.append('scorm_file', file);
        formData.append('duration_minutes', document.getElementById('scormChapterDuration').value || 0);

        // Get CSRF token from meta tag or form
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            document.querySelector('input[name="_token"]')?.value;
        if (!csrfToken) {
            alert('Security error: CSRF token not found');
            return;
        }
        formData.append('_token', csrfToken);

        // Submit
        fetch(`/admin/courses/${currentCourseId}/scorm-chapters`, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeAddScormChapterModal();
                    loadScormChapters();
                    alert('SCORM chapter added successfully!');
                } else {
                    alert('Error: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to add SCORM chapter: ' + error.message);
            });
    });

    function editScormChapter(chapterId) {
        console.log('Edit chapter:', chapterId);
        // TODO: Implement edit functionality
    }

    function deleteScormChapter(chapterId) {
        if (!confirm('Are you sure you want to delete this SCORM chapter?')) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            document.querySelector('input[name="_token"]')?.value;

        fetch(`/admin/courses/${currentCourseId}/scorm-chapters/${chapterId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadScormChapters();
                    alert('SCORM chapter deleted successfully!');
                } else {
                    alert('Error: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete SCORM chapter: ' + error.message);
            });
    }
</script>