{{-- Edit Page Form --}}
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Page</h2>

        <form id="editPageForm" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="page_slug" value="{{ $page->slug }}">

            {{-- Title Field --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Page Title *
                </label>
                <input type="text" id="title" name="title" required value="{{ $page->title }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    placeholder="Enter page title">
                <p class="mt-1 text-sm text-gray-500">Current slug: <code
                        class="bg-gray-100 px-2 py-1 rounded">{{ $page->slug }}</code></p>
            </div>

            {{-- Course Selection --}}
            <div>
                <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Select Course (Optional)
                </label>
                <select id="course_id" name="course_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">-- Choose a course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ $page->course_id == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-sm text-gray-500">Select a course to link this page to a course</p>
            </div>

            {{-- Content Field with Rich Text Editor --}}
            <div>
                <label for="editor-container" class="block text-sm font-medium text-gray-700 mb-2">
                    Page Content * (Rich Text Editor)
                </label>
                <div id="editor-toolbar"
                    class="bg-gray-100 border border-gray-300 rounded-t-lg p-2 flex flex-wrap gap-1">
                    <button type="button" class="toolbar-btn" data-format="bold" title="Bold (Ctrl+B)">
                        <strong>B</strong>
                    </button>
                    <button type="button" class="toolbar-btn" data-format="italic" title="Italic (Ctrl+I)">
                        <em>I</em>
                    </button>
                    <button type="button" class="toolbar-btn" data-format="underline" title="Underline (Ctrl+U)">
                        <u>U</u>
                    </button>
                    <div class="w-px bg-gray-300 mx-1"></div>
                    <button type="button" class="toolbar-btn" data-format="insertUnorderedList" title="Bullet List">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button type="button" class="toolbar-btn" data-format="insertOrderedList" title="Numbered List">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div class="w-px bg-gray-300 mx-1"></div>
                    <button type="button" class="toolbar-btn" data-format="createLink" title="Insert Link">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.868 11.168c.519.26 1.006.249 1.734-.215l3.685-2.3c.374-.233.767-.072 1.141.254.374.326.467.742.093.975l-3.685 2.3c-1.339.84-2.674.922-3.99.269-1.316-.653-2.583-1.867-3.768-3.052C3.88 6.945 2.666 5.68 2.013 4.364 1.36 3.048 1.442 1.713 2.282.374l3.685-2.3c.374-.233.742-.27 1.141.056.398.326.31.694-.063.927l-3.685 2.3c-.728.464-.975.952-.216 1.471.76.52 1.874 1.038 3.157 2.08 1.283 1.042 2.294 2.149 2.943 3.356z" />
                        </svg>
                    </button>
                </div>
                <div id="editor-container" class="border border-t-0 border-gray-300 rounded-b-lg bg-white p-4 min-h-64"
                    contenteditable="true" style="outline: none;">{!! $page->content !!}</div>
                <textarea id="content" name="content" required style="display:none;"></textarea>
                <p class="mt-1 text-sm text-gray-500">Use the toolbar to format your content</p>
            </div>

            {{-- Info Text --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-700">
                    <strong>Note:</strong> Status halaman ini mengikuti course yang dipilih.
                    @if($page->course)
                        Saat ini course <strong>{{ $page->course->title }}</strong> berstatus
                        <strong>{{ $page->course->is_published ? 'Published' : 'Draft' }}</strong>.
                    @else
                        Pilih course untuk mengatur status halaman.
                    @endif
                </p>
            </div>

            {{-- Metadata --}}
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600">
                    <strong>Created:</strong> {{ $page->created_at->format('M d, Y \a\t H:i') }}<br>
                    <strong>Last Updated:</strong> {{ $page->updated_at->format('M d, Y \a\t H:i') }}
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                    class="px-6 py-2 bg-primary hover:bg-primaryDark text-white rounded-lg font-medium transition-colors">
                    Update Page
                </button>
                <button type="button" onclick="loadAdminPage('pages')"
                    class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .toolbar-btn {
        padding: 0.5rem 0.75rem;
        background-color: white;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        cursor: pointer;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .toolbar-btn:hover {
        background-color: #f3f4f6;
        border-color: #9ca3af;
    }

    .toolbar-btn.active {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    #editor-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        line-height: 1.6;
    }
</style>

<script>
    const editorContainer = document.getElementById('editor-container');
    const contentTextarea = document.getElementById('content');
    const toolbarBtns = document.querySelectorAll('.toolbar-btn');

    // Sync editor content to textarea
    function syncContent() {
        contentTextarea.value = editorContainer.innerHTML;
    }

    // Initialize content
    syncContent();

    // Editor event listeners
    editorContainer.addEventListener('input', syncContent);
    editorContainer.addEventListener('blur', syncContent);

    // Toolbar button click handlers
    toolbarBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const format = btn.dataset.format;

            if (format === 'createLink') {
                const url = prompt('Enter URL:');
                if (url) {
                    document.execCommand('createLink', false, url);
                }
            } else {
                document.execCommand(format, false, null);
            }

            editorContainer.focus();
            syncContent();
            updateToolbarState();
        });
    });

    // Update toolbar state
    function updateToolbarState() {
        toolbarBtns.forEach(btn => {
            const format = btn.dataset.format;
            if (document.queryCommandState(format)) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    editorContainer.addEventListener('mouseup', updateToolbarState);
    editorContainer.addEventListener('keyup', updateToolbarState);

    // Form submission
    document.getElementById('editPageForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const pageSlug = document.querySelector('input[name="page_slug"]').value;
        // Get editor content
        const content = editorContainer.innerHTML;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const data = {
            title: document.getElementById('title').value,
            content: content,
            course_id: document.getElementById('course_id').value || null
        };

        fetch(`/admin/pages/${pageSlug}`, {
            method: 'PUT',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Page updated successfully!');
                    if (data.redirect) {
                        loadAdminPage('pages');
                    }
                } else {
                    alert('Error: ' + (data.message || 'Failed to update page'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the page: ' + error.message);
            });
    });
</script>