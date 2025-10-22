@extends('layouts.app')

@section('title', 'Learn: ' . $course->title)

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b sticky top-0 z-40">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}"
                            class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-lg lg:text-xl font-bold text-gray-900">{{ $course->title }}</h1>
                            <p class="text-xs lg:text-sm text-gray-500">SCORM Course</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-6xl mx-auto">
                <!-- SCORM Package Container -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    {{-- Display SCORM Entry Point --}}
                    @php
                        // Use the secure route to serve SCORM files with entry point path
                        $entryPoint = $course->scorm_entry_point ?? 'index.html';
                        $scormUrl = route('courses.scorm-file', ['course' => $course->id, 'path' => $entryPoint]);
                    @endphp

                    <!-- Debug Info -->
                    {{-- Removed for production --}}

                    <div class="w-full" style="height: 100vh; max-height: 900px;">
                        <iframe id="scorm-player" src="{{ $scormUrl }}" style="width: 100%; height: 100%; border: none;"
                            title="SCORM Course Content" allow="fullscreen" onload="console.log('SCORM iframe loaded')"
                            onerror="console.error('SCORM iframe failed to load')">
                        </iframe>
                    </div>
                </div>

                <!-- Course Info Footer -->
                <div class="grid md:grid-cols-3 gap-6 mt-8">
                    {{-- Course Details --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Information</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Title</p>
                                <p class="font-medium text-gray-900">{{ $course->title }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Level</p>
                                <p class="font-medium text-gray-900 capitalize">{{ $course->level ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">SCORM Version</p>
                                <p class="font-medium text-gray-900">{{ $course->scorm_version ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Course Description --}}
                    <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">About This Course</h3>
                        <p class="text-gray-700 leading-relaxed">
                            {{ $course->description ?? 'No description available.' }}
                        </p>
                    </div>
                </div>

                <!-- Navigation Footer -->
                <div class="mt-8 flex justify-center pb-8">
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Return to Dashboard
                    </a>
                </div>
        </main>
    </div>

    <script>
        // Handle SCORM communication if needed
        // This is a basic implementation. You may need to extend this
        // to handle SCORM API calls and progress tracking

        document.addEventListener('DOMContentLoaded', function () {
            const iframe = document.getElementById('scorm-player');

            // Try to access iframe content (may be restricted by CORS)
            try {
                if (iframe && iframe.contentWindow) {
                    // You can implement SCORM tracking here
                    console.log('SCORM Player loaded');
                }
            } catch (e) {
                console.log('Unable to access SCORM content: ' + e.message);
            }
        });
    </script>
@endsection