@extends('layouts.app')

@section('title', 'Create Course - Admin Dashboard')

@section('content')
    <div class="min-h-screen bg-gray-50">
        {{-- Header --}}
        <header class="bg-white shadow-sm border-b">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center space-x-4">
                        <a href="/admin/dashboard" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Create New Course</h1>
                            <p class="text-gray-600">Choose the type of course you want to create</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="max-w-4xl mx-auto">
                {{-- Course Type Selection --}}
                <div class="grid md:grid-cols-2 gap-6">
                    {{-- Regular Course Card --}}
                    <a href="{{ route('admin.courses.create-regular') }}"
                        class="group block p-8 bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-blue-300 transition-all">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-blue-200 transition">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 mb-2">Regular Course</h2>
                            <p class="text-gray-600 mb-6">Create a course with video lessons, chapters, and custom content
                            </p>
                            <div
                                class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg group-hover:bg-blue-700 transition font-medium">
                                Create Regular Course
                            </div>
                        </div>

                        {{-- Features --}}
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-3">Features:</p>
                            <ul class="text-sm text-gray-600 space-y-2">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mt-0.5 mr-2 text-green-600 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Multiple chapters & lessons</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mt-0.5 mr-2 text-green-600 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Video content support</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mt-0.5 mr-2 text-green-600 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Progress tracking</span>
                                </li>
                            </ul>
                        </div>
                    </a>

                    {{-- SCORM Course Card --}}
                    <a href="{{ route('admin.courses.create-scorm-form') }}"
                        class="group block p-8 bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-green-300 transition-all">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-200 transition">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 mb-2">Upload SCORM</h2>
                            <p class="text-gray-600 mb-6">Import a SCORM package directly from a ZIP file</p>
                            <div
                                class="inline-block px-6 py-2 bg-green-600 text-white rounded-lg group-hover:bg-green-700 transition font-medium">
                                Upload SCORM Package
                            </div>
                        </div>

                        {{-- Features --}}
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-3">Features:</p>
                            <ul class="text-sm text-gray-600 space-y-2">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mt-0.5 mr-2 text-green-600 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>SCORM 1.2 & 2004 support</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mt-0.5 mr-2 text-green-600 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Up to 100MB package size</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mt-0.5 mr-2 text-green-600 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Automatic metadata extraction</span>
                                </li>
                            </ul>
                        </div>
                    </a>
                </div>

                {{-- Info Section --}}
                <div class="mt-12 grid md:grid-cols-2 gap-6">
                    <div class="p-6 bg-blue-50 border border-blue-200 rounded-lg">
                        <h3 class="font-semibold text-blue-900 mb-2">Regular Course</h3>
                        <p class="text-sm text-blue-800">Best for courses with custom structure, multiple chapters, and
                            video lessons. You have full control over the content organization.</p>
                    </div>
                    <div class="p-6 bg-green-50 border border-green-200 rounded-lg">
                        <h3 class="font-semibold text-green-900 mb-2">SCORM Course</h3>
                        <p class="text-sm text-green-800">Best for importing pre-packaged courses built with SCORM
                            standards. Simply upload the ZIP file and it's ready to go.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection