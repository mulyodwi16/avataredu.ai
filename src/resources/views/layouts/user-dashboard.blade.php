<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'AvatarEdu.ai'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <aside id="dashSidebar"
            class="fixed inset-y-0 left-0 w-72 bg-white border-r border-gray-200 shadow-sm z-30 transform translate-x-0 transition-transform duration-300 ease-in-out lg:translate-x-0">
            <div class="p-6">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-8">
                    <div class="h-10 w-10 grid place-items-center rounded-xl bg-primary text-white font-black text-lg">A
                    </div>
                    <span class="text-xl font-bold text-primary">Avataredu.ai</span>
                </a>

                <!-- User Info -->
                <div class="mb-8 p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-primary/20 overflow-hidden">
                            <img src="https://i.pravatar.cc/64?img=5" alt="avatar" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">{{ auth()->user()->name }}</div>
                            <div class="text-sm text-gray-500">Student</div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-1">
                    @php
                        $items = [
                            ['label' => 'Dashboard', 'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z', 'id' => 'dashboard', 'type' => 'internal'],
                            ['label' => 'My Account', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'id' => 'account', 'type' => 'internal'],
                            ['label' => 'Browse Courses', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'id' => 'courses', 'type' => 'internal'],
                            ['label' => 'My Collection', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16', 'id' => 'collection', 'type' => 'internal'],
                            ['label' => 'Purchase History', 'icon' => 'M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'id' => 'purchase-history', 'type' => 'internal'],
                        ];
                    @endphp

                    @foreach ($items as $item)
                        <button onclick="loadPage('{{ $item['id'] }}')"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-primary/5 hover:text-primary transition-colors w-full text-left {{ request()->routeIs('dashboard') && $item['id'] == 'dashboard' ? 'bg-primary/10 text-primary' : '' }}"
                            data-page="{{ $item['id'] }}" <svg class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                            </svg>
                            <span class="font-medium">{{ $item['label'] }}</span>
                            </a>
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
                    <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>

                    <!-- Search and Actions -->
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <input type="text" placeholder="Search courses..."
                                class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-5-5V9.09c0-2.55-2.04-4.63-4.56-4.63S6 6.54 6 9.09V12l-5 5h5m4 0v1a3 3 0 11-6 0v-1" />
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>

    <script>
        // Mobile sidebar toggle
        const burger = document.getElementById('dashBurger');
        const sidebar = document.getElementById('dashSidebar');
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

        // Single page navigation
        function loadPage(pageId) {
            // Update active nav item
            document.querySelectorAll('[data-page]').forEach(item => {
                item.classList.remove('bg-primary/10', 'text-primary');
                item.classList.add('text-gray-700');
            });

            const activeItem = document.querySelector(`[data-page="${pageId}"]`);
            if (activeItem) {
                activeItem.classList.add('bg-primary/10', 'text-primary');
                activeItem.classList.remove('text-gray-700');
            }

            // Update page title
            const pageTitles = {
                'dashboard': 'My Dashboard',
                'account': 'Account Settings',
                'courses': 'Browse Courses',
                'collection': 'Course Collection',
                'purchase-history': 'Purchase History'
            };

            document.querySelector('h1').textContent = pageTitles[pageId] || 'Dashboard';

            // Load page content
            const contentDiv = document.querySelector('[data-content]');
            if (contentDiv) {
                contentDiv.innerHTML = '<div class="flex justify-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div></div>';

                // Fetch content via AJAX
                console.log(`Loading page: ${pageId}`);
                fetch(`/api/dashboard/${pageId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => {
                        console.log(`Response status for ${pageId}:`, response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log(`Data received for ${pageId}:`, data);
                        if (data.html) {
                            contentDiv.innerHTML = data.html;
                        } else if (data.error) {
                            contentDiv.innerHTML = `<div class="text-center py-8 text-red-600">Error: ${data.error}</div>`;
                        } else {
                            contentDiv.innerHTML = '<div class="text-center py-8 text-red-600">No content received</div>';
                        }
                    })
                    .catch(error => {
                        console.error(`Error loading ${pageId}:`, error);
                        contentDiv.innerHTML = `<div class="text-center py-8 text-red-600">Error loading content: ${error.message}</div>`;
                    });
            }
        }

        // Function to view course details
        function viewCourseDetails(courseId) {
            console.log('Loading course details for course ID:', courseId);

            const contentDiv = document.querySelector('[data-content]');
            if (contentDiv) {
                contentDiv.innerHTML = '<div class="flex justify-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div></div>';

                // Fetch course detail via AJAX
                fetch(`/api/courses/${courseId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.html) {
                            contentDiv.innerHTML = data.html;
                        } else {
                            contentDiv.innerHTML = '<div class="text-center py-8 text-red-600">Error loading course details</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        contentDiv.innerHTML = '<div class="text-center py-8 text-red-600">Error loading course details</div>';
                    });
            }
        }

        // Global enrollment functions
        function enrollFree(courseId) {
            if (confirm('Enroll in this free course?')) {
                enrollInCourse(courseId);
            }
        }

        function enrollPaid(courseId) {
            showPaymentModal(courseId);
        }

        function enrollInCourse(courseId) {
            fetch(`/courses/${courseId}/enroll`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    payment_method: 'free'
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Successfully enrolled!');
                        loadPage('collection');
                    } else {
                        alert('Enrollment failed: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred during enrollment.');
                });
        }

        function showPaymentModal(courseId) {
            const modal = createPaymentModal(courseId);
            document.body.appendChild(modal);

            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('.modal-content').classList.remove('scale-95');
                modal.querySelector('.modal-content').classList.add('scale-100');
            }, 10);
        }

        function createPaymentModal(courseId) {
            // Remove any existing modal first
            const existingModal = document.getElementById('payment-modal');
            if (existingModal) {
                existingModal.remove();
            }

            const modal = document.createElement('div');
            modal.id = 'payment-modal';
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 opacity-0 transition-opacity duration-300';
            modal.innerHTML = `
                <div class="modal-content bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform scale-95 transition-transform duration-300">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Complete Your Purchase</h3>
                        <p class="text-gray-600">Choose your payment method to enroll in this course</p>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="border-2 border-primary bg-primary/5 rounded-lg p-4 cursor-pointer payment-method" data-method="dummy_card">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Dummy Credit Card</div>
                                    <div class="text-sm text-gray-600">**** **** **** 1234</div>
                                </div>
                                <div class="ml-auto">
                                    <div class="w-5 h-5 border-2 border-primary rounded-full bg-primary"></div>
                                </div>
                            </div>
                        </div>

                        <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer payment-method" data-method="dummy_wallet">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Dummy E-Wallet</div>
                                    <div class="text-sm text-gray-600">Balance: Rp 999.999.999</div>
                                </div>
                                <div class="ml-auto">
                                    <div class="w-5 h-5 border-2 border-gray-300 rounded-full"></div>
                                </div>
                            </div>
                        </div>

                        <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer payment-method" data-method="dummy_transfer">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Bank Transfer</div>
                                    <div class="text-sm text-gray-600">Dummy Bank Account</div>
                                </div>
                                <div class="ml-auto">
                                    <div class="w-5 h-5 border-2 border-gray-300 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" class="btn-cancel flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="button" class="btn-pay flex-1 px-6 py-3 bg-primary text-white rounded-lg hover:bg-primaryDark transition-colors font-semibold" data-course-id="${courseId}">
                            Pay Now
                        </button>
                    </div>
                </div>
            `;

            modal.querySelectorAll('.payment-method').forEach(method => {
                method.addEventListener('click', function () {
                    modal.querySelectorAll('.payment-method').forEach(m => {
                        m.classList.remove('border-primary', 'bg-primary/5');
                        m.classList.add('border-gray-200');
                        m.querySelector('.ml-auto div').classList.remove('bg-primary');
                        m.querySelector('.ml-auto div').classList.add('border-gray-300');
                    });

                    this.classList.add('border-primary', 'bg-primary/5');
                    this.classList.remove('border-gray-200');
                    this.querySelector('.ml-auto div').classList.add('bg-primary');
                    this.querySelector('.ml-auto div').classList.remove('border-gray-300');
                });
            });

            // Add event listeners for buttons
            const cancelBtn = modal.querySelector('.btn-cancel');
            const payBtn = modal.querySelector('.btn-pay');

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    console.log('Cancel button clicked');
                    closePaymentModal();
                });
            }

            if (payBtn) {
                payBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    console.log('Pay button clicked');
                    const courseIdFromBtn = this.getAttribute('data-course-id') || courseId;
                    showInvoiceModal(courseIdFromBtn, modal);
                });
            }

            return modal;
        }

        function closePaymentModal() {
            console.log('Closing payment modal...');
            const modal = document.querySelector('.fixed.inset-0') || document.getElementById('payment-modal');
            if (modal) {
                console.log('Modal found, closing...');
                modal.classList.add('opacity-0');
                const modalContent = modal.querySelector('.modal-content');
                if (modalContent) {
                    modalContent.classList.add('scale-95');
                    modalContent.classList.remove('scale-100');
                }
                setTimeout(() => {
                    modal.remove();
                    console.log('Modal removed');
                }, 300);
            } else {
                console.error('Payment modal not found');
            }
        }

        function showInvoiceModal(courseId, paymentModal) {
            console.log('Showing invoice for course:', courseId);

            // Close payment modal first
            if (paymentModal) {
                paymentModal.classList.add('opacity-0');
                setTimeout(() => {
                    paymentModal.remove();
                }, 300);
            }

            // Get course details for invoice
            const courseCard = document.querySelector(`[data-course-id="${courseId}"]`)?.closest('.course-card');
            let courseName = 'Course';
            let coursePrice = '49000';

            if (courseCard) {
                const titleElement = courseCard.querySelector('.course-title, h3, .text-xl');
                if (titleElement) courseName = titleElement.textContent.trim();

                const priceElement = courseCard.querySelector('.course-price, .text-primary');
                if (priceElement) {
                    const priceText = priceElement.textContent.replace(/[^\d]/g, '');
                    if (priceText) coursePrice = priceText;
                }
            }

            // Create invoice modal
            const invoiceModal = document.createElement('div');
            invoiceModal.id = 'invoice-modal';
            invoiceModal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 opacity-0 transition-opacity duration-300';

            const invoiceNumber = 'INV-' + Date.now();
            const currentDate = new Date().toLocaleDateString('id-ID');

            invoiceModal.innerHTML = `
                <div class="modal-content bg-white rounded-2xl p-8 max-w-lg w-full mx-4 transform scale-95 transition-transform duration-300">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Invoice</h3>
                        <p class="text-gray-600">Please review your purchase details</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="text-sm text-gray-600">Invoice Number</p>
                                <p class="font-semibold">${invoiceNumber}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Date</p>
                                <p class="font-semibold">${currentDate}</p>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-700">${courseName}</span>
                                <span class="font-semibold">Rp ${parseInt(coursePrice).toLocaleString('id-ID')}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm text-gray-600 mb-3">
                                <span>Quantity: 1</span>
                                <span>Subtotal: Rp ${parseInt(coursePrice).toLocaleString('id-ID')}</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2">
                                <div class="flex justify-between items-center font-bold text-lg">
                                    <span>Total</span>
                                    <span class="text-primary">Rp ${parseInt(coursePrice).toLocaleString('id-ID')}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" class="btn-cancel-invoice flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="button" class="btn-confirm-payment flex-1 px-6 py-3 bg-primary text-white rounded-lg hover:bg-primaryDark transition-colors font-semibold" data-course-id="${courseId}" data-invoice="${invoiceNumber}">
                            Confirm Payment
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(invoiceModal);

            // Add event listeners
            const cancelBtn = invoiceModal.querySelector('.btn-cancel-invoice');
            const confirmBtn = invoiceModal.querySelector('.btn-confirm-payment');

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function () {
                    closeInvoiceModal();
                });
            }

            if (confirmBtn) {
                confirmBtn.addEventListener('click', function () {
                    const courseId = this.getAttribute('data-course-id');
                    const invoiceNumber = this.getAttribute('data-invoice');
                    processPaymentConfirmation(courseId, invoiceNumber);
                });
            }

            // Show modal with animation
            setTimeout(() => {
                invoiceModal.classList.remove('opacity-0');
                invoiceModal.querySelector('.modal-content').classList.remove('scale-95');
                invoiceModal.querySelector('.modal-content').classList.add('scale-100');
            }, 10);
        }

        function closeInvoiceModal() {
            const modal = document.getElementById('invoice-modal');
            if (modal) {
                modal.classList.add('opacity-0');
                modal.querySelector('.modal-content').classList.add('scale-95');
                modal.querySelector('.modal-content').classList.remove('scale-100');
                setTimeout(() => {
                    modal.remove();
                }, 300);
            }
        }

        function processPaymentConfirmation(courseId, invoiceNumber) {
            console.log('Processing payment confirmation for course:', courseId);

            const confirmBtn = document.querySelector('.btn-confirm-payment');
            if (confirmBtn) {
                const originalText = confirmBtn.textContent;
                confirmBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                confirmBtn.disabled = true;
            }

            // Get selected payment method from previous modal (default to dummy_card)
            const selectedMethod = 'dummy_card';

            fetch(`/courses/${courseId}/enroll`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    payment_method: selectedMethod,
                    invoice_number: invoiceNumber
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeInvoiceModal();
                        showSuccessMessage('Payment successful! Course has been added to your collection.');

                        // Refresh dashboard to show updated collection and purchase history
                        setTimeout(() => {
                            loadPage('collection');
                        }, 2000);
                    } else {
                        if (confirmBtn) {
                            confirmBtn.textContent = 'Confirm Payment';
                            confirmBtn.disabled = false;
                        }
                        alert('Payment failed: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (confirmBtn) {
                        confirmBtn.textContent = 'Confirm Payment';
                        confirmBtn.disabled = false;
                    }
                    alert('An error occurred during payment processing.');
                });
        }

        function processPayment(courseId) {
            console.log('Processing payment for course:', courseId);

            const modal = document.querySelector('.fixed.inset-0') || document.getElementById('payment-modal');
            if (!modal) {
                console.error('Payment modal not found');
                alert('Error: Payment modal not found');
                return;
            }

            const selectedMethod = modal.querySelector('.payment-method.border-primary')?.dataset.method || 'dummy_card';
            console.log('Selected payment method:', selectedMethod);

            const payButton = modal.querySelector('button[onclick*="processPayment"]') || modal.querySelector('.btn-pay');
            if (!payButton) {
                console.error('Pay button not found');
                alert('Error: Pay button not found');
                return;
            }

            const originalText = payButton.textContent;
            payButton.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            payButton.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                console.error('CSRF token not found');
                payButton.textContent = originalText;
                payButton.disabled = false;
                alert('Error: Security token not found');
                return;
            }

            fetch(`/courses/${courseId}/enroll`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    payment_method: selectedMethod
                })
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        closePaymentModal();
                        showSuccessMessage('Payment successful! You are now enrolled in this course.');
                        viewCourseDetails(courseId);
                    } else {
                        payButton.textContent = originalText;
                        payButton.disabled = false;
                        alert('Payment failed: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    payButton.textContent = originalText;
                    payButton.disabled = false;
                    alert('An error occurred during payment processing.');
                });
        }

        function showSuccessMessage(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-accent text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
            toast.innerHTML = `
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 5000);
        }

        function continueLearning(courseId) {
            console.log('Continue learning course:', courseId);
            // Navigate to course learning page
            window.location.href = `/courses/${courseId}/learn`;
        }
    </script>
</body>

</html>