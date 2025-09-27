@extends('layouts.app')

@section('title', 'Avataredu.ai - Learn Together')

@section('content')
    {{-- Hero --}}
    <section class="bg-gradient-to-b from-blue-50 to-white py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-5xl md:text-6xl font-extrabold mb-4">
                    Learn fast, <span class="text-blue-600">achieve results</span>
                </h1>
                <p class="text-gray-600 text-lg mb-8">Short courses to upgrade your skills. Starting at Rp49,000</p>
                <div class="flex justify-center gap-4">
                    <a href="#browse"
                        class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-blue-700">Browse
                        Modules</a>
                    <a href="{{ route('login') }}"
                        class="bg-white text-gray-800 px-6 py-2.5 rounded-lg font-semibold border border-gray-300 hover:bg-gray-50">Sign
                        in with Google</a>
                </div>
            </div>
            <div class="flex justify-center">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&auto=format&fit=crop&q=80"
                    alt="Students learning together" class="rounded-2xl shadow-lg w-full max-w-3xl mt-12">
            </div>
        </div>
    </section>

    {{-- Featured Modules --}}
    <section id="browse" class="py-20">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold mb-2 text-center">This Week's Featured Modules</h2>
            <p class="text-gray-600 mb-8 text-center">Hand-picked courses to boost your career</p>

            @php
                $courses = [
                    ['title' => 'Prompt Engineering Mastery', 'category' => 'AI & Data', 'description' => 'Master the art of crafting effective AI prompts for better results', 'image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=400&h=250&fit=crop&q=80', 'price' => 49000, 'duration' => '2 hours', 'level' => 'Beginner', 'rating' => 4.8, 'reviews' => 127, 'badge' => 'Bestseller'],
                    ['title' => 'Python for Beginners', 'category' => 'Programming', 'description' => 'Learn Python programming from scratch with practical projects', 'image' => 'https://images.unsplash.com/photo-1587620962725-abab7fe55159?w=400&h=250&fit=crop&q=80', 'price' => 49000, 'duration' => '3 hours', 'level' => 'Beginner', 'rating' => 4.8, 'reviews' => 127, 'badge' => 'Bestseller'],
                    ['title' => 'UI/UX Design Fundamentals', 'category' => 'Design', 'description' => 'Create beautiful and user-friendly interfaces', 'image' => 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?w=400&h=250&fit=crop&q=80', 'price' => 55000, 'duration' => '2.5 hours', 'level' => 'Intermediate', 'rating' => 4.8, 'reviews' => 127, 'badge' => 'Bestseller'],
                ];
              @endphp

            <div class="grid md:grid-cols-3 gap-6">
                @each('partials.course-card', array_slice($courses, 0, 3), 'course')
            </div>
        </div>
    </section>

    {{-- How It Works (icons bulat biru) --}}
    @include('partials.how-it-works')

    {{-- Testimonials (bintang kuning) --}}
    @include('partials.testimonials')
@endsection