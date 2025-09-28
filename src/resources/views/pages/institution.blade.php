@extends('layouts.app')

@section('title', 'Institution Learning')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-primary/10 via-white to-white">
        {{-- Include the header from dashboard --}}
        @include('partials.dashboard-header')

        <div class="mx-auto max-w-7xl px-4 py-6 lg:py-8 flex gap-6">
            {{-- Include the sidebar from dashboard --}}
            @include('partials.dashboard-sidebar')

            {{-- Main Content --}}
            <main class="flex-1 space-y-6">
                {{-- Institution Header --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Institution Learning</h2>
                        <p class="text-gray-500">Access your institution's learning materials and courses</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <select
                            class="px-4 py-2 rounded-lg border border-gray-200 bg-white focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option>All Institutions</option>
                            <option>University A</option>
                            <option>College B</option>
                            <option>School C</option>
                        </select>
                    </div>
                </div>

                {{-- Active Institution --}}
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row items-start gap-6">
                            <div class="w-20 h-20 rounded-xl bg-primary/10 grid place-items-center">
                                <svg class="w-12 h-12 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">University of Technology</h3>
                                <p class="text-gray-500 mb-4">Computer Science Department</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-medium">
                                        Student ID: CS2025001
                                    </span>
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                                        Active
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Current Semester --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Current Semester</h3>
                        <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-medium">
                            Fall 2025
                        </span>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @php
                            $courses = [
                                [
                                    'code' => 'CS301',
                                    'name' => 'Advanced Database Systems',
                                    'instructor' => 'Dr. John Smith',
                                    'schedule' => 'Mon, Wed 10:00-11:30',
                                    'progress' => 65,
                                    'assignments' => 3,
                                    'next_class' => '2 days'
                                ],
                                [
                                    'code' => 'CS302',
                                    'name' => 'Machine Learning',
                                    'instructor' => 'Dr. Sarah Johnson',
                                    'schedule' => 'Tue, Thu 13:00-14:30',
                                    'progress' => 45,
                                    'assignments' => 2,
                                    'next_class' => 'Tomorrow'
                                ],
                                [
                                    'code' => 'CS303',
                                    'name' => 'Software Engineering',
                                    'instructor' => 'Prof. Michael Brown',
                                    'schedule' => 'Wed, Fri 15:00-16:30',
                                    'progress' => 80,
                                    'assignments' => 1,
                                    'next_class' => '3 days'
                                ],
                            ];
                        @endphp

                        @foreach($courses as $course)
                            <div class="bg-white p-6 rounded-xl shadow">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">{{ $course['code'] }}</span>
                                        <h4 class="font-semibold text-gray-800">{{ $course['name'] }}</h4>
                                    </div>
                                    <button class="text-gray-400 hover:text-gray-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="space-y-3 mb-4">
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>{{ $course['instructor'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $course['schedule'] }}</span>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">Course Progress</span>
                                        <span class="font-medium text-gray-700">{{ $course['progress'] }}%</span>
                                    </div>
                                    <div class="relative h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="absolute inset-y-0 left-0 bg-primary transition-all duration-300"
                                            style="width: {{ $course['progress'] }}%"></div>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800">{{ $course['assignments'] }}</div>
                                        <div class="text-xs text-gray-500">Pending Assignments</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800">{{ $course['next_class'] }}</div>
                                        <div class="text-xs text-gray-500">Next Class</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Academic Calendar --}}
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="p-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Academic Calendar</h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-4">
                            @php
                                $events = [
                                    ['date' => '2025-10-01', 'title' => 'Midterm Examinations Begin', 'type' => 'exam'],
                                    ['date' => '2025-10-15', 'title' => 'Course Registration for Next Semester', 'type' => 'registration'],
                                    ['date' => '2025-11-01', 'title' => 'Research Project Submission', 'type' => 'deadline'],
                                    ['date' => '2025-11-15', 'title' => 'Department Meeting', 'type' => 'meeting'],
                                    ['date' => '2025-12-01', 'title' => 'Final Examinations Begin', 'type' => 'exam'],
                                ];
                            @endphp

                            @foreach($events as $event)
                                <div class="flex items-center gap-4">
                                    <div class="w-16 text-center">
                                        <div class="text-sm font-medium text-gray-500">
                                            {{ \Carbon\Carbon::parse($event['date'])->format('M') }}
                                        </div>
                                        <div class="text-2xl font-bold text-gray-800">
                                            {{ \Carbon\Carbon::parse($event['date'])->format('d') }}
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-800">{{ $event['title'] }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ ucfirst($event['type']) }}
                                        </div>
                                    </div>
                                    <div>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                                    {{ $event['type'] === 'exam' ? 'bg-red-100 text-red-700' : '' }}
                                                    {{ $event['type'] === 'registration' ? 'bg-blue-100 text-blue-700' : '' }}
                                                    {{ $event['type'] === 'deadline' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                    {{ $event['type'] === 'meeting' ? 'bg-green-100 text-green-700' : '' }}">
                                            {{ \Carbon\Carbon::parse($event['date'])->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection