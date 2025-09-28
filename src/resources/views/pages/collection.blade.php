@extends('layouts.app')

@section('title', 'My Collection')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-primary/10 via-white to-white">
        {{-- Include the header from dashboard --}}
        @include('partials.dashboard-header')

        <div class="mx-auto max-w-7xl px-4 py-6 lg:py-8 flex gap-6">
            {{-- Include the sidebar from dashboard --}}
            @include('partials.dashboard-sidebar')

            {{-- Main Content --}}
            <main class="flex-1 space-y-6">
                {{-- Collection Header --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">My Collection</h2>
                        <p class="text-gray-500">Your saved courses and learning materials</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <select
                            class="px-4 py-2 rounded-lg border border-gray-200 bg-white focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option>All Items</option>
                            <option>Courses</option>
                            <option>Books</option>
                            <option>Videos</option>
                        </select>
                    </div>
                </div>

                {{-- Collection Stats --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @php
                        $stats = [
                            ['label' => 'Saved Courses', 'value' => '12', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            ['label' => 'Total Hours', 'value' => '45', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['label' => 'Completed', 'value' => '8', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['label' => 'Certificates', 'value' => '3', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ];
                    @endphp

                    @foreach($stats as $stat)
                        <div class="bg-white p-4 rounded-xl shadow">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-primary/10 grid place-items-center">
                                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $stat['icon'] }}" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-gray-800">{{ $stat['value'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $stat['label'] }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Saved Courses --}}
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Saved Courses</h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @php
                            $savedCourses = [
                                [
                                    'title' => 'Complete Web Development Bootcamp',
                                    'progress' => 75,
                                    'lastAccessed' => '2 days ago',
                                    'img' => 'https://images.unsplash.com/photo-1593720213428-28a5b9e94613?w=480&h=320&fit=crop&q=80'
                                ],
                                [
                                    'title' => 'Mobile App Development with Flutter',
                                    'progress' => 30,
                                    'lastAccessed' => '1 week ago',
                                    'img' => 'https://images.unsplash.com/photo-1617040619263-41c5a9ca7521?w=480&h=320&fit=crop&q=80'
                                ],
                                [
                                    'title' => 'Digital Marketing Masterclass',
                                    'progress' => 100,
                                    'lastAccessed' => '3 days ago',
                                    'img' => 'https://via.placeholder.com/480x320.png?text=Marketing'
                                ],
                            ];
                        @endphp

                        @foreach($savedCourses as $course)
                            <div class="bg-white rounded-xl shadow overflow-hidden">
                                <img src="{{ $course['img'] }}" alt="course thumbnail" class="w-full h-40 object-cover">
                                <div class="p-4">
                                    <h4 class="font-semibold text-gray-800 mb-2">{{ $course['title'] }}</h4>
                                    <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                        <span>Last accessed {{ $course['lastAccessed'] }}</span>
                                        <span>{{ $course['progress'] }}% Complete</span>
                                    </div>
                                    <div class="relative h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="absolute inset-y-0 left-0 bg-primary transition-all duration-300"
                                            style="width: {{ $course['progress'] }}%"></div>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between">
                                        <button class="text-sm text-red-500 hover:text-red-600">
                                            Remove from collection
                                        </button>
                                        <a href="#" class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primaryDark">
                                            Continue
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Saved Books & Resources --}}
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Saved Books & Resources</h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @php
                            $resources = [
                                ['title' => 'Python Programming Guide', 'type' => 'PDF', 'size' => '2.5 MB'],
                                ['title' => 'JavaScript ES6 Cheatsheet', 'type' => 'PDF', 'size' => '1.2 MB'],
                                ['title' => 'Git Version Control Tutorial', 'type' => 'Video', 'size' => '45 min'],
                                ['title' => 'SQL Database Design', 'type' => 'PDF', 'size' => '3.1 MB'],
                            ];
                        @endphp

                        @foreach($resources as $resource)
                            <div class="bg-white p-4 rounded-xl shadow">
                                <div class="w-10 h-10 mb-3 rounded-lg bg-primary/10 grid place-items-center">
                                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h4 class="font-medium text-gray-800 mb-1">{{ $resource['title'] }}</h4>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>{{ $resource['type'] }}</span>
                                    <span>{{ $resource['size'] }}</span>
                                </div>
                                <button
                                    class="mt-3 w-full px-3 py-1.5 rounded-lg border border-primary text-primary hover:bg-primary/5">
                                    Download
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection