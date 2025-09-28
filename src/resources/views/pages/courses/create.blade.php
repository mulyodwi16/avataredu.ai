@extends('layouts.app')

@section('title', isset($course) ? 'Edit Course: ' . $course->title : 'Create New Course')

@push('head')
    {{-- Include any additional CSS/JS for rich text editor, etc --}}
@endpush

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-primary/10 via-white to-white">
        {{-- Header --}}
        @include('partials.dashboard-header')

        <div class="mx-auto max-w-7xl px-4 py-6 lg:py-8 flex gap-6">
            {{-- Sidebar --}}
            @include('partials.dashboard-sidebar')

            {{-- Main Content --}}
            <main class="flex-1">
                <div class="bg-white rounded-2xl shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">
                            {{ isset($course) && $course->title ? 'Edit Course: ' . $course->title : 'Create New Course' }}
                        </h1>
                    </div>

                    <form
                        action="{{ isset($course) ? route('creator.courses.update', $course) : route('creator.courses.store') }}"
                        method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @if(isset($course))
                            @method('PUT')
                        @endif

                        {{-- Basic Information --}}
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Course Title</label>
                                <input type="text" name="title" value="{{ old('title', isset($course) ? $course->title : '') }}"
                                    class="w-full px-4 py-2 rounded-lg border {{ $errors->has('title') ? 'border-red-400 ring-2 ring-red-500/20' : 'border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary' }} transition"
                                    required>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select name="category_id"
                                    class="w-full px-4 py-2 rounded-lg border {{ $errors->has('category_id') ? 'border-red-400 ring-2 ring-red-500/20' : 'border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary' }} transition">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"                                             {{ old('category_id', isset($course) ? $course->category_id : '') == $category->id ? 'selected' : '' }}>>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price (IDR)</label>
                                <input type="number" name="price" value="{{ old('price', isset($course) ? $course->price : '') }}"
                                    class="w-full px-4 py-2 rounded-lg border {{ $errors->has('price') ? 'border-red-400 ring-2 ring-red-500/20' : 'border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary' }} transition"
                                    min="0" step="1000" required>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Level</label>
                                <select name="level"
                                    class="w-full px-4 py-2 rounded-lg border {{ $errors->has('level') ? 'border-red-400 ring-2 ring-red-500/20' : 'border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary' }} transition">
                                    @foreach(['beginner', 'intermediate', 'advanced'] as $level)
                                        <option value="{{ $level }}"                                             {{ old('level', isset($course) ? $course->level : '') == $level ? 'selected' : '' }}>>
                                            {{ ucfirst($level) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('level')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Course Thumbnail --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Course Thumbnail {{ !isset($course) ? '(Required)' : '' }}
                            </label>
                            <div class="mt-1 flex items-center gap-4">
                                @if(isset($course) && $course->thumbnail && Storage::disk('public')->exists($course->thumbnail))
                                    <img src="{{ Storage::url($course->thumbnail) }}" alt="Current thumbnail"
                                        class="w-48 h-32 object-cover rounded-lg">
                                @endif
                                <div class="flex-1">
                                    <input type="file" name="thumbnail" accept="image/*"
                                        class="w-full px-4 py-2 rounded-lg border {{ $errors->has('thumbnail') ? 'border-red-400 ring-2 ring-red-500/20' : 'border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary' }} transition">
                                    <p class="mt-1 text-sm text-gray-500">
                                        Recommended size: 1280x720px. Max size: 2MB
                                    </p>
                                    @error('thumbnail')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Course Description</label>
                            <textarea name="description" rows="4"
                                class="w-full px-4 py-2 rounded-lg border {{ $errors->has('description') ? 'border-red-400 ring-2 ring-red-500/20' : 'border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary' }} transition"
                                required>{{ old('description', isset($course) ? $course->description : '') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Learning Outcomes --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Learning Outcomes</label>
                            <div class="space-y-2" id="learning-outcomes">
                                @if(isset($course) && is_array($course->learning_outcomes))
                                    @foreach($course->learning_outcomes as $index => $outcome)
                                        <div class="flex gap-2">
                                            <input type="text" name="learning_outcomes[]" value="{{ $outcome }}"
                                                class="flex-1 px-4 py-2 rounded-lg border border-gray-200">
                                            <button type="button" onclick="this.parentElement.remove()"
                                                class="px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg">
                                                Remove
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" onclick="addLearningOutcome()"
                                class="mt-2 text-primary hover:text-primaryDark">
                                + Add Learning Outcome
                            </button>
                            @error('learning_outcomes')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Requirements --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Course Requirements</label>
                            <div class="space-y-2" id="requirements">
                                @if(isset($course) && is_array($course->requirements))
                                    @foreach($course->requirements as $index => $requirement)
                                        <div class="flex gap-2">
                                            <input type="text" name="requirements[]" value="{{ $requirement }}"
                                                class="flex-1 px-4 py-2 rounded-lg border border-gray-200">
                                            <button type="button" onclick="this.parentElement.remove()"
                                                class="px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg">
                                                Remove
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" onclick="addRequirement()"
                                class="mt-2 text-primary hover:text-primaryDark">
                                + Add Requirement
                            </button>
                            @error('requirements')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        @if(isset($course))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Course Status</label>
                                <select name="status"
                                    class="w-full px-4 py-2 rounded-lg border {{ $errors->has('status') ? 'border-red-400 ring-2 ring-red-500/20' : 'border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary' }} transition">
                                    <option value="draft" {{ isset($course) && $course->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ isset($course) && $course->status == 'published' ? 'selected' : '' }}>Published
                                    </option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        {{-- Submit Buttons --}}
                        <div class="flex justify-end gap-4">
                            <a href="{{ route('creator.courses.index') }}"
                                class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary text-white hover:bg-primaryDark">
                                {{ isset($course) ? 'Update Course' : 'Create Course' }}
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function addLearningOutcome() {
            const container = document.getElementById('learning-outcomes');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
            <input type="text" name="learning_outcomes[]"
                class="flex-1 px-4 py-2 rounded-lg border border-gray-200"
                placeholder="Enter a learning outcome">
            <button type="button" onclick="this.parentElement.remove()"
                class="px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg">
                Remove
            </button>
        `;
            container.appendChild(div);
        }

        function addRequirement() {
            const container = document.getElementById('requirements');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
            <input type="text" name="requirements[]"
                class="flex-1 px-4 py-2 rounded-lg border border-gray-200"
                placeholder="Enter a requirement">
            <button type="button" onclick="this.parentElement.remove()"
                class="px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg">
                Remove
            </button>
        `;
            container.appendChild(div);
        }
    </script>
@endpush