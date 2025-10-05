<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50 flex">
        <!-- Sidebar -->
        <div class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0 lg:z-50">
            <div class="flex-1 flex flex-col min-h-0 bg-white border-r border-gray-200">
                <!-- Sidebar header -->
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <!-- Logo -->
                    <div class="flex items-center flex-shrink-0 px-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-primary rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">A</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Avataredu.ai</p>
                                <p class="text-xs text-gray-500">Admin Panel</p>
                            </div>
                        </div>
                    </div>

                    <!-- User info -->
                    <div class="mt-6 px-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img class="h-8 w-8 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF"
                                    alt="{{ auth()->user()->name }}">
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ auth()->user()->isSuperAdmin() ? 'Super Administrator' : 'Administrator' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="mt-8 flex-1 px-4 space-y-1">
                        <button onclick="loadAdminPage('dashboard')"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/5 hover:text-primary transition-colors w-full text-left bg-primary/10 text-primary"
                            data-admin-page="dashboard">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </button>

                        <button onclick="loadAdminPage('courses')"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-primary/5 hover:text-primary transition-colors w-full text-left"
                            data-admin-page="courses">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span class="font-medium">Manage Courses</span>
                        </button>

                        @if(auth()->user()->isSuperAdmin())
                            <button onclick="loadAdminPage('users')"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-primary/5 hover:text-primary transition-colors w-full text-left"
                                data-admin-page="users">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                <span class="font-medium">Manage Users</span>
                            </button>
                        @endif
                    </nav>

                    <!-- Logout -->
                    <div class="px-4 pb-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors w-full text-left">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="font-medium">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar overlay -->
        <div class="lg:hidden" id="mobile-sidebar-overlay">
            <div class="fixed inset-0 flex z-40">
                <div class="fixed inset-0 bg-gray-600 bg-opacity-75" id="sidebar-overlay"></div>
                <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button type="button" id="close-sidebar"
                            class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <!-- Mobile sidebar content same as desktop -->
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="lg:pl-64 flex flex-col flex-1">
            <!-- Top nav -->
            <div class="sticky top-0 z-10 lg:hidden pl-1 pt-1 sm:pl-3 sm:pt-3 bg-gray-50">
                <button type="button" id="open-sidebar"
                    class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Page content -->
            <main class="flex-1">
                <div class="py-6">
                    <div class="mx-auto px-4 sm:px-6 lg:px-8">
                        <!-- Page header -->
                        <div class="mb-8">
                            <h1 class="text-2xl font-semibold text-gray-900">Dashboard Overview</h1>
                        </div>

                        <!-- Dynamic content will be loaded here -->
                        <div data-admin-content>
                            @include('admin.partials.dashboard-content')
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        const openSidebar = document.getElementById('open-sidebar');
        const closeSidebar = document.getElementById('close-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const mobileSidebar = document.getElementById('mobile-sidebar-overlay');

        function toggleSidebar() {
            mobileSidebar.classList.toggle('hidden');
        }

        if (openSidebar) {
            openSidebar.addEventListener('click', toggleSidebar);
        }
        if (closeSidebar) {
            closeSidebar.addEventListener('click', toggleSidebar);
        }
        if (overlay) {
            overlay.addEventListener('click', toggleSidebar);
        }

        // Admin single page navigation
        function loadAdminPage(pageId) {
            // Update active nav item
            document.querySelectorAll('[data-admin-page]').forEach(item => {
                item.classList.remove('bg-primary/10', 'text-primary');
                item.classList.add('text-gray-700');
            });

            const activeItem = document.querySelector(`[data-admin-page="${pageId}"]`);
            if (activeItem) {
                activeItem.classList.add('bg-primary/10', 'text-primary');
                activeItem.classList.remove('text-gray-700');
            }

            // Update page title
            const pageTitles = {
                'dashboard': 'Dashboard Overview',
                'courses': 'Manage Courses',
                'users': 'Manage Users'
            };

            const titleElement = document.querySelector('h1');
            if (titleElement) {
                titleElement.textContent = pageTitles[pageId] || 'Admin Dashboard';
            }

            // Load page content with loading indicator
            const contentDiv = document.querySelector('[data-admin-content]');
            if (contentDiv) {
                // Show loading spinner
                contentDiv.innerHTML = `
                    <div class="flex justify-center items-center py-20">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4"></div>
                            <p class="text-gray-600">Loading...</p>
                        </div>
                    </div>
                `;

                // Create abort controller for timeout
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 8000); // 8 second timeout

                // Fetch content via AJAX with timeout
                fetch(`/admin/api/dashboard/${pageId}`, {
                    signal: controller.signal,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => {
                        clearTimeout(timeoutId);
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.html) {
                            contentDiv.innerHTML = data.html;
                        } else {
                            throw new Error('No content received');
                        }
                    })
                    .catch(error => {
                        clearTimeout(timeoutId);
                        console.error('Error loading page:', pageId, error);

                        let errorMessage = 'Connection error';
                        if (error.name === 'AbortError') {
                            errorMessage = 'Request timed out';
                        } else if (error.message.includes('HTTP')) {
                            errorMessage = 'Server error (' + error.message + ')';
                        } else {
                            errorMessage = error.message || 'Unknown error';
                        }

                        contentDiv.innerHTML = `
                        <div class="text-center py-12">
                            <div class="text-red-600 mb-4">
                                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <p class="font-semibold">${errorMessage}</p>
                                <p class="text-sm text-gray-500">Please try again</p>
                            </div>
                            <button onclick="loadAdminPage('${pageId}')" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Retry
                            </button>
                        </div>
                    `;
                    });
            }

            // Close mobile sidebar if open
            if (mobileSidebar && !mobileSidebar.classList.contains('hidden')) {
                toggleSidebar();
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            // Default to courses page if no specific page is set
            if (window.location.pathname === '/admin/dashboard') {
                // Keep dashboard as default
                loadAdminPage('dashboard');
            }
        });

        // Quick action for creating course from empty state
        window.showCreateCourseModal = function () {
            loadAdminPage('courses/create');
        };

        // Delete course function
        function deleteCourse(courseId) {
            if (!confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/admin/api/courses/${courseId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Reload courses page to show updated list
                        loadAdminPage('courses');
                    } else {
                        alert('Error: ' + (data.error || data.message || 'Failed to delete course'));
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    alert('Failed to delete course. Please try again.');
                });
        }

        // Delete user function (Super Admin only)
        function deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/admin/api/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        loadAdminPage('users');
                    } else {
                        alert('Error: ' + (data.error || data.message || 'Failed to delete user'));
                    }
                })
                .catch(error => {
                    console.error('Delete user error:', error);
                    alert('Failed to delete user. Please try again.');
                });
        }

        // Make admin function (Super Admin only)  
        function makeAdmin(userId) {
            if (!confirm('Are you sure you want to make this user an admin?')) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/admin/api/users/${userId}/make-admin`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        loadAdminPage('users');
                    } else {
                        alert('Error: ' + (data.error || data.message || 'Failed to make user admin'));
                    }
                })
                .catch(error => {
                    console.error('Make admin error:', error);
                    alert('Failed to make user admin. Please try again.');
                });
        }

        // Initial load for courses if hash present
        if (window.location.hash === '#courses') {
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    loadAdminPage('courses');
                }, 100);
            });
        }
    </script>
</body>

</html>