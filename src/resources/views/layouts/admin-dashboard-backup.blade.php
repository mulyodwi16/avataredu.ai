<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - ' . config('app.name', 'AvatarEdu.ai'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <aside id="adminSidebar"
            class="fixed inset-y-0 left-0 w-72 bg-white border-r border-gray-200 shadow-sm z-30 transform translate-x-0 transition-transform duration-300 ease-in-out lg:translate-x-0">
            <div class="p-6">
                <!-- Logo -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 mb-8">
                    <div class="h-10 w-10 grid place-items-center rounded-xl bg-primary text-white font-black text-lg">A
                    </div>
                    <span class="text-xl font-bold text-primary">Avataredu.ai</span>
                    <span class="text-xs bg-primary/20 text-primary px-2 py-1 rounded-full font-semibold">
                        @if(auth()->user()->isSuperAdmin())
                            SUPER ADMIN
                        @else
                            ADMIN
                        @endif
                    </span>
                </a>

                <!-- User Info -->
                <div class="mb-8 p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-primary/20 overflow-hidden">
                            <img src="https://i.pravatar.cc/64?img=1" alt="avatar" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">{{ auth()->user()->name }}</div>
                            <div class="text-sm text-gray-500">
                                @if(auth()->user()->isSuperAdmin())
                                    Super Administrator
                                @else
                                    Administrator
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-1">
                    @php
                        $items = [
                            ['label' => 'Dashboard', 'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z', 'id' => 'dashboard', 'type' => 'internal'],
                            ['label' => 'Manage Courses', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'id' => 'courses', 'type' => 'internal'],
                        ];

                        // Super admin only items
                        if (auth()->user()->isSuperAdmin()) {
                            $items[] = ['label' => 'Manage Users', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z', 'id' => 'users', 'type' => 'internal'];
                        }
                    @endphp

                    @foreach ($items as $item)
                        @if(isset($item['type']) && $item['type'] == 'external')
                            <a href="{{ $item['url'] }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-primary/5 hover:text-primary transition-colors w-full text-left">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $item['icon'] }}" />
                                </svg>
                                <span class="font-medium">{{ $item['label'] }}</span>
                            </a>
                        @else
                            <button onclick="loadAdminPage('{{ $item['id'] }}')"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-primary/5 hover:text-primary transition-colors w-full text-left {{ request()->routeIs('admin.dashboard') && $item['id'] == 'dashboard' ? 'bg-primary/10 text-primary' : '' }}"
                                data-admin-page="{{ $item['id'] }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $item['icon'] }}" />
                                </svg>
                                <span class="font-medium">{{ $item['label'] }}</span>
                            </button>
                        @endif
                    @endforeach
                </nav>

                <!-- Logout -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors w-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="ml-72">
            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Admin Dashboard')</h1>
                        @if(isset($breadcrumbs))
                            <nav class="flex mt-1" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                    @foreach($breadcrumbs as $breadcrumb)
                                        <li class="inline-flex items-center">
                                            @if(!$loop->last)
                                                <a href="{{ $breadcrumb['url'] }}"
                                                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary">
                                                    {{ $breadcrumb['name'] }}
                                                </a>
                                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                                </svg>
                                            @else
                                                <span class="text-sm font-medium text-gray-500">{{ $breadcrumb['name'] }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </nav>
                        @endif
                    </div>

                    <!-- Actions and User Info -->
                    <div class="flex items-center gap-4">
                        @yield('header-actions')

                        <!-- Quick Actions -->
                        <div class="flex items-center gap-2">
                            <!-- Notifications -->
                            <button class="relative p-2 text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-5-5V9.09c0-2.55-2.04-4.63-4.56-4.63S6 6.54 6 9.09V12l-5 5h5m4 0v1a3 3 0 11-6 0v-1" />
                                </svg>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>

    <script>
        // Mobile sidebar toggle
        const burger = document.getElementById('adminBurger');
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        if (burger) {
            burger.addEventListener('click', toggleSidebar);
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
        }

        // Delete Course Function (Superadmin only)
        async function deleteCourse(courseId) {
            if (!confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch(`/admin/api/courses/${courseId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    // Refresh courses page
                    loadAdminPage('courses');
                } else {
                    alert(data.error || 'Failed to delete course');
                }
            } catch (error) {
                console.error('Delete error:', error);
                alert('An error occurred while deleting the course');
            }
        }
    </script>

    @stack('scripts')
</body>

</html>