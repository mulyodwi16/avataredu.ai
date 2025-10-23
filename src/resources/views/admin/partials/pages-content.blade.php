{{-- Pages List Content --}}
<div class="space-y-6">
    {{-- Header with Create Button --}}
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-900">All Pages</h2>
        <button onclick="loadAdminPage('pages/create')"
            class="bg-primary hover:bg-primaryDark text-white px-4 py-2 rounded-lg font-medium transition-colors">
            + Create New Page
        </button>
    </div>

    {{-- Pages Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($pages && count($pages) > 0)
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Title</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Course</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Created</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($pages as $page)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $page->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($page->course)
                                    {{ $page->course->title }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($page->course)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $page->course->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $page->course->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        No Course
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $page->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <button onclick="loadAdminPage('pages/{{ $page->slug }}/edit')"
                                    class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>
                                <button onclick="deletePageConfirm('{{ $page->slug }}')"
                                    class="inline-flex items-center px-3 py-1 rounded-lg bg-red-50 text-red-700 hover:bg-red-100 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="px-6 py-12 text-center">
                <div class="mb-4">
                    <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No pages yet</h3>
                <p class="text-gray-600 mb-4">Create your first page to get started.</p>
                <button onclick="loadAdminPage('pages/create')"
                    class="inline-flex items-center px-4 py-2 rounded-lg bg-primary text-white hover:bg-primaryDark transition-colors">
                    Create First Page
                </button>
            </div>
        @endif
    </div>
</div>

<script>
    function deletePageConfirm(pageSlug) {
        if (confirm('Are you sure you want to delete this page? This action cannot be undone.')) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(`/admin/pages/${pageSlug}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Page deleted successfully!');
                    loadAdminPage('pages');
                } else {
                    alert('Error: ' + (data.message || 'Failed to delete page'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the page');
            });
        }
    }
</script>
