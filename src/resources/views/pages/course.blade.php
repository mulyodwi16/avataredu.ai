@extends('layouts.app')

@section('title', 'Course List')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-primary/10 via-white to-white">
        {{-- Include the header from dashboard --}}
        @include('partials.dashboard-header')

        <div class="mx-auto max-w-7xl px-4 py-6 lg:py-8 flex gap-6">
            {{-- Include the sidebar from dashboard --}}
            @include('partials.dashboard-sidebar')

            {{-- Main Content --}}
            <main class="flex-1 space-y-6">
                {{-- Course Header --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Course List</h2>
                        <p class="text-gray-500">Browse through our extensive collection of courses</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <select
                            class="px-4 py-2 rounded-lg border border-gray-200 bg-white focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option>All Categories</option>
                            <option>Technology</option>
                            <option>Business</option>
                            <option>Design</option>
                            <option>Marketing</option>
                        </select>
                        <select
                            class="px-4 py-2 rounded-lg border border-gray-200 bg-white focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option>Sort by: Latest</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>Most Popular</option>
                        </select>
                    </div>
                </div>

                {{-- Course Grid --}}
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $courses = [
                            [
                                'title' => 'Introduction to Python Programming',
                                'description' => 'Learn the basics of Python programming language from scratch.',
                                'price' => 35000,
                                'instructor' => 'John Doe',
                                'level' => 'Beginner',
                                'duration' => '6 weeks',
                                'rating' => 4.5,
                                'img' => 'https://images.unsplash.com/photo-1526379095098-d400fd0bf935?w=480&h=320&fit=crop&q=80'
                            ],
                            [
                                'title' => 'Advanced Web Development',
                                'description' => 'Master modern web development techniques and frameworks.',
                                'price' => 45000,
                                'instructor' => 'Jane Smith',
                                'level' => 'Advanced',
                                'duration' => '8 weeks',
                                'rating' => 4.8,
                                'img' => 'https://images.unsplash.com/photo-1593720213428-28a5b9e94613?w=480&h=320&fit=crop&q=80'
                            ],
                            [
                                'title' => 'Data Science Fundamentals',
                                'description' => 'Understanding the basics of data science and analytics.',
                                'price' => 40000,
                                'instructor' => 'Mike Johnson',
                                'level' => 'Intermediate',
                                'duration' => '10 weeks',
                                'rating' => 4.6,
                                'img' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=480&h=320&fit=crop&q=80'
                            ],
                            [
                                'title' => 'UI/UX Design Principles',
                                'description' => 'Learn modern design principles and tools.',
                                'price' => 38000,
                                'instructor' => 'Sarah Wilson',
                                'level' => 'Beginner',
                                'duration' => '6 weeks',
                                'rating' => 4.7,
                                'img' => 'https://via.placeholder.com/480x320.png?text=UI+Design'
                            ],
                            [
                                'title' => 'Digital Marketing Strategy',
                                'description' => 'Comprehensive guide to digital marketing.',
                                'price' => 42000,
                                'instructor' => 'Tom Brown',
                                'level' => 'Intermediate',
                                'duration' => '7 weeks',
                                'rating' => 4.4,
                                'img' => 'https://via.placeholder.com/480x320.png?text=Marketing'
                            ],
                            [
                                'title' => 'Machine Learning Basics',
                                'description' => 'Introduction to machine learning algorithms.',
                                'price' => 50000,
                                'instructor' => 'Emily Chen',
                                'level' => 'Advanced',
                                'duration' => '12 weeks',
                                'rating' => 4.9,
                                'img' => 'https://via.placeholder.com/480x320.png?text=ML+Course'
                            ],
                        ];
                    @endphp

                    @foreach($courses as $course)
                        <article class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
                            <div class="relative">
                                <img src="{{ $course['img'] }}" alt="course thumbnail" class="w-full h-48 object-cover">
                                <div class="absolute top-2 right-2 flex items-center gap-1 bg-white/90 rounded-full px-2 py-1">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="text-sm font-medium">{{ $course['rating'] }}</span>
                                </div>
                            </div>

                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-[11px] px-2 py-0.5 rounded-full bg-primary/10 text-primary font-medium">
                                        {{ $course['level'] }}
                                    </span>
                                    <span class="text-[11px] px-2 py-0.5 rounded-full bg-accent/10 text-accent font-medium">
                                        {{ $course['duration'] }}
                                    </span>
                                </div>

                                <h3 class="font-semibold text-gray-800 mb-1">{{ $course['title'] }}</h3>
                                <p class="text-sm text-gray-500 mb-3">{{ $course['description'] }}</p>

                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-6 h-6 rounded-full bg-primary/20 overflow-hidden">
                                        <img src="https://i.pravatar.cc/48?u={{ $course['instructor'] }}" alt="instructor"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-sm text-gray-600">{{ $course['instructor'] }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="text-primary font-bold">
                                        Rp {{ number_format($course['price'], 0, ',', '.') }}
                                    </div>
                                    <a href="#" class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primaryDark">
                                        Enroll Now
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="flex justify-center mt-8">
                    <nav class="flex items-center gap-1">
                        <a href="#"
                            class="w-10 h-10 grid place-items-center rounded-lg border border-gray-200 hover:bg-gray-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-10 h-10 grid place-items-center rounded-lg border border-primary bg-primary text-white">1</a>
                        <a href="#"
                            class="w-10 h-10 grid place-items-center rounded-lg border border-gray-200 hover:bg-gray-50">2</a>
                        <a href="#"
                            class="w-10 h-10 grid place-items-center rounded-lg border border-gray-200 hover:bg-gray-50">3</a>
                        <span class="w-10 h-10 grid place-items-center">...</span>
                        <a href="#"
                            class="w-10 h-10 grid place-items-center rounded-lg border border-gray-200 hover:bg-gray-50">10</a>
                        <a href="#"
                            class="w-10 h-10 grid place-items-center rounded-lg border border-gray-200 hover:bg-gray-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </nav>
                </div>
            </main>
        </div>
    </div>
@endsection