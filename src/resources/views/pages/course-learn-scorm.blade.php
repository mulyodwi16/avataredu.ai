@extends('layouts.app')

@section('title', 'Learn: ' . $course->title)

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Mobile Header -->
        <div class="lg:hidden bg-white shadow-sm border-b p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <button id="mobile-menu-toggle" class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold text-gray-900 truncate">{{ $course->title }}</h1>
                </div>
                <a href="{{ route('dashboard') }}" class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </div>
        </div>

        <div class="flex relative">
            <!-- Sidebar -->
            <div id="sidebar" class="w-80 bg-white shadow-xl fixed lg:relative h-full lg:h-screen overflow-y-auto z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
                <!-- Sidebar Header -->
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold">{{ $course->title }}</h2>
                        <a href="{{ route('dashboard') }}" class="hidden lg:block text-white/80 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Progress Bar -->
                    @php
                        $chapters = ($course->scormChapters && $course->scormChapters->count() > 0)
                            ? $course->scormChapters
                            : ($course->chapters ?? collect());
                        $totalChapters = $chapters->count();
                        $completedChapters = 0;
                        $progressPercentage = 0;
                    @endphp

                    <div class="mb-2">
                        <div class="flex justify-between text-sm text-white/90 mb-3">
                            <span class="font-medium">Course Progress</span>
                            <span class="bg-white/20 px-2 py-1 rounded-full text-xs font-semibold">{{ round($progressPercentage) }}%</span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-3">
                            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-3 rounded-full transition-all duration-500 ease-out"
                                 style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <div class="text-xs text-white/80 mt-2 text-center">
                            {{ $completedChapters }} of {{ $totalChapters }} chapters
                        </div>
                    </div>
                </div>

                <!-- SCORM/Chapters List -->
                <div class="p-6">
                    @if($chapters && $chapters->count() > 0)
                        <div class="space-y-3">
                            @foreach($chapters as $chapter)
                                @php
                                    $isCurrent = request('chapter') == $chapter->id ||
                                               (request('chapter') === null && $loop->first);
                                @endphp

                                <div class="group flex items-center gap-4 p-4 rounded-xl cursor-pointer transition-all duration-200
                                            {{ $isCurrent ? 'bg-gradient-to-r from-blue-50 to-purple-50 border-2 border-blue-200 shadow-md transform scale-[1.02]' : 'hover:bg-gray-50 hover:shadow-sm border border-transparent' }}"
                                     onclick="loadChapter({{ $chapter->id }})">
                                    <!-- Chapter Icon -->
                                    <div class="flex-shrink-0">
                                        @if($isCurrent)
                                            <div class="w-6 h-6 border-2 border-blue-400 rounded-full bg-white flex items-center justify-center">
                                                <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                                            </div>
                                        @else
                                            <div class="w-6 h-6 border-2 border-gray-300 rounded-full group-hover:border-blue-300 transition-colors"></div>
                                        @endif
                                    </div>

                                    <!-- Chapter Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors truncate">
                                            @if($course->content_type === 'scorm_multi' && isset($chapter->order))
                                                Chapter {{ $chapter->order }}
                                            @else
                                                {{ $chapter->title }}
                                            @endif
                                        </div>
                                        @if($chapter->duration_minutes ?? false)
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $chapter->duration_minutes }} min
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">No chapters available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Close Sidebar Button (Mobile) -->
            <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 lg:hidden hidden z-30" onclick="toggleSidebar()"></div>

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <header class="bg-white shadow-sm border-b sticky top-0 z-20">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between items-center py-4">
                            <div class="flex items-center space-x-4">
                                <button class="lg:hidden p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors" onclick="toggleSidebar()">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    </svg>
                                </button>
                                <div>
                                    <h1 class="text-lg lg:text-xl font-bold text-gray-900">{{ $course->title }}</h1>
                                    <p class="text-xs lg:text-sm text-gray-500">SCORM Course</p>
                                </div>
                            </div>
                            <a href="{{ route('dashboard') }}"
                                class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <main class="px-4 sm:px-6 lg:px-8 py-8">
                    <!-- SCORM Package Container -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        {{-- Display SCORM Entry Point --}}
                        @php
                            $selectedChapterId = request('chapter');
                            $currentChapter = null;

                            // Try SCORM chapters first
                            if ($selectedChapterId && $course->scormChapters && $course->scormChapters->count() > 0) {
                                $currentChapter = $course->scormChapters->firstWhere('id', $selectedChapterId);
                            }

                            // Try regular chapters
                            if (!$currentChapter && $selectedChapterId && $course->chapters && $course->chapters->count() > 0) {
                                $currentChapter = $course->chapters->firstWhere('id', $selectedChapterId);
                            }

                            // Fallback to first SCORM chapter
                            if (!$currentChapter && $course->scormChapters && $course->scormChapters->count() > 0) {
                                $currentChapter = $course->scormChapters->first();
                            }

                            // Fallback to first regular chapter
                            if (!$currentChapter && $course->chapters && $course->chapters->count() > 0) {
                                $currentChapter = $course->chapters->first();
                            }

                            // Build SCORM URL
                            if ($currentChapter && isset($currentChapter->scorm_package_path)) {
                                $entryPoint = $currentChapter->scorm_entry_point ?? 'index.html';
                                $folderName = basename($currentChapter->scorm_package_path);
                                $scormUrl = route('courses.scorm-file', [
                                    'course' => $course->id,
                                    'path' => $folderName . '/' . $entryPoint
                                ]);
                            } else {
                                $entryPoint = $course->scorm_entry_point ?? 'index.html';
                                $scormUrl = route('courses.scorm-file', ['course' => $course->id, 'path' => $entryPoint]);
                            }
                        @endphp

                        <div class="w-full" style="height: 70vh;">
                            <iframe id="scorm-player" src="{{ $scormUrl }}" style="width: 100%; height: 100%; border: none;"
                                title="SCORM Course Content" allow="fullscreen" onload="console.log('SCORM iframe loaded')"
                                onerror="console.error('SCORM iframe failed to load')">
                            </iframe>
                        </div>
                    </div>

                    <!-- Course Info -->
                    @if($currentChapter)
                        <div class="mt-8 grid md:grid-cols-2 gap-6">
                            {{-- Chapter Details --}}
                            <div id="chapter-details" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Chapter Information</h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Title</p>
                                        <p class="font-medium text-gray-900" id="chapter-title">
                                            @if($course->content_type === 'scorm_multi' && isset($currentChapter->order))
                                                Chapter {{ $currentChapter->order }}
                                            @else
                                                {{ $currentChapter->title ?? 'N/A' }}
                                            @endif
                                        </p>
                                    </div>
                                    @if($currentChapter->duration_minutes ?? false)
                                        <div>
                                            <p class="text-sm text-gray-600">Duration</p>
                                            <p class="font-medium text-gray-900">{{ $currentChapter->duration_minutes }} minutes</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Chapter Description --}}
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">About This Chapter</h3>
                                <p class="text-gray-700 leading-relaxed" id="chapter-description">
                                    {{ $currentChapter->description ?? 'No description available.' }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Navigation Footer -->
                    <div class="mt-8 flex justify-center pb-8">
                        <a href="{{ route('dashboard') }}"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Return to Dashboard
                        </a>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function loadChapter(chapterId) {
            // Close mobile sidebar
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }

            // Load the chapter
            window.location.href = `?chapter=${chapterId}`;
        }

        document.getElementById('mobile-menu-toggle').addEventListener('click', toggleSidebar);
    </script>
@endsection