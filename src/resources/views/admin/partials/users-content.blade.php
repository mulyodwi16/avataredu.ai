{{-- User Management Content --}}
<div class="bg-white rounded-lg shadow-sm">
    <!-- Header -->
    <div class="bg-primary text-white p-4 rounded-t-lg">
        <h2 class="text-xl font-bold flex items-center">
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            User Management
        </h2>
    </div>

    <!-- Search and Add User -->
    <div class="p-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <input type="text" id="search-users" placeholder="Search users..."
                    class="border border-gray-300 rounded px-3 py-2 w-64 text-sm">
                <button onclick="searchUsers()"
                    class="bg-primary text-white px-4 py-2 rounded text-sm hover:bg-primaryDark transition-colors">
                    Search
                </button>
            </div>
            <button onclick="openAddUserModal()"
                class="bg-primary text-white px-4 py-2 rounded text-sm hover:bg-primaryDark transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z">
                    </path>
                </svg>
                Add New User
            </button>
        </div>
    </div>

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-primary text-white">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">ID</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Username</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Name</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Email</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if($users && $users->count() > 0)
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $user->id }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">
                                {{ $user->email ? explode('@', $user->email)[0] : 'N/A' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $user->name }}</td>
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Role Badge -->
                                    @php
                                        $roleDisplay = [
                                            'admin' => 'Admin',
                                            'super_admin' => 'Super Admin',
                                            'user' => 'User'
                                        ];
                                        $roleColors = [
                                            'admin' => 'bg-blue-100 text-blue-800',
                                            'super_admin' => 'bg-purple-100 text-purple-800',
                                            'user' => 'bg-green-100 text-green-800'
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 py-1 rounded text-xs font-medium {{ $roleColors[$user->role] ?? $roleColors['user'] }}">
                                        {{ $roleDisplay[$user->role] ?? 'User' }}
                                    </span>

                                    <!-- Action Buttons -->
                                    <button onclick="editUser({{ $user->id }})" class="text-blue-600 hover:text-blue-800 p-1"
                                        title="Edit User">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                            </path>
                                        </svg>
                                    </button>

                                    @if(auth()->user()->id !== $user->id)
                                        <button onclick="deleteUser({{ $user->id }})" class="text-red-600 hover:text-red-800 p-1"
                                            title="Delete User">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                <!-- User Details Row -->
                                <div class="mt-2 text-xs text-gray-500">
                                    <div><strong>Role:</strong> {{ $roleDisplay[$user->role] ?? 'User' }}</div>
                                    <div><strong>Created at:</strong> {{ $user->created_at->format('M j, Y') }}</div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                                <p>No users found</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users && method_exists($users, 'hasPages') && $users->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            <div class="flex justify-center">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Scripts -->
<script>
    function searchUsers() {
        const search = document.getElementById('search-users').value;
        const currentUrl = new URL(window.location.href);

        // Update URL with search parameter
        if (search) {
            currentUrl.searchParams.set('search', search);
        } else {
            currentUrl.searchParams.delete('search');
        }

        // Reload the admin content with search
        loadAdminPage('users', { search: search });
    }

    function openAddUserModal() {
        // Implement add user modal
        alert('Add User functionality to be implemented');
    }

    function editUser(userId) {
        // Fetch user data and show edit modal
        fetch(`/admin/api/users/${userId}`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch user');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const user = data.user;
                    // Create edit modal
                    const modal = document.createElement('div');
                    modal.id = 'editUserModal';
                    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
                    modal.innerHTML = `
                        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                            <h2 class="text-xl font-bold mb-4">Edit User</h2>
                            <form id="editUserForm">
                                <input type="hidden" name="user_id" value="${user.id}">
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                    <input type="text" name="name" value="${user.name}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" value="${user.email}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                    <select name="role" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                                        <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                                        <option value="super_admin" ${user.role === 'super_admin' ? 'selected' : ''}>Super Admin</option>
                                    </select>
                                </div>

                                <div class="flex gap-2 justify-end">
                                    <button type="button" onclick="closeEditModal()" 
                                        class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                                    <button type="submit" 
                                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">Save</button>
                                </div>
                            </form>
                        </div>
                    `;
                    document.body.appendChild(modal);

                    // Handle form submission
                    document.getElementById('editUserForm').addEventListener('submit', function (e) {
                        e.preventDefault();
                        const formData = new FormData(this);
                        const data = {
                            name: formData.get('name'),
                            email: formData.get('email'),
                            role: formData.get('role')
                        };

                        fetch(`/admin/api/users/${userId}`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('User updated successfully');
                                    closeEditModal();
                                    location.reload();
                                } else {
                                    alert('Error: ' + (data.message || 'Failed to update user'));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred while updating the user');
                            });
                    });

                    // Close modal when clicking outside
                    modal.addEventListener('click', function (e) {
                        if (e.target === this) closeEditModal();
                    });
                } else {
                    alert('Error: ' + (data.message || 'Failed to load user'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while loading user data');
            });
    }

    function closeEditModal() {
        const modal = document.getElementById('editUserModal');
        if (modal) modal.remove();
    }

    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/admin/api/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('User deleted successfully');
                        location.reload();
                    } else {
                        // Show error message from server
                        const errorMsg = data.error || data.message || 'Failed to delete user';
                        alert('Error: ' + errorMsg);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the user');
                });
        }
    }

    // Enable search on Enter key
    document.getElementById('search-users').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            searchUsers();
        }
    });
</script>