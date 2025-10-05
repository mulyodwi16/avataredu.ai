@props([
    'course',
    'showEnrollButton' => true,
    'showProgress' => false,
    'progress' => 0,
    'class' => ''
])

<article {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow group hover:shadow-lg transition-all duration-300 overflow-hidden ' . $class]) }}>
    <div class="relative overflow-hidden rounded-t-2xl">
        <img 
            src="{{ $course->thumbnail_url }}" 
            alt="{{ $course->title }}"
            class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
        >
        
        {{-- Level Badge --}}
        <div class="absolute top-3 left-3">
            @if($course->level === 'beginner')
                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                    {{ ucfirst($course->level) }}
                </span>
            @elseif($course->level === 'intermediate')
                <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                    {{ ucfirst($course->level) }}
                </span>
            @elseif($course->level === 'advanced')
                <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                    {{ ucfirst($course->level) }}
                </span>
            @else
                <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                    {{ ucfirst($course->level) }}
                </span>
            @endif
        </div>

        {{-- Price Badge --}}
        @if($course->price > 0)
            <div class="absolute top-3 right-3">
                <span class="px-2 py-1 rounded-full text-xs font-bold bg-primary text-white">
                    {{ $course->formatted_price }}
                </span>
            </div>
        @else
            <div class="absolute top-3 right-3">
                <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-600 text-white">
                    FREE
                </span>
            </div>
        @endif
    </div>

    <div class="p-5">
        {{-- Category --}}
        <div class="flex items-center gap-2 mb-2">
            <span class="text-xs text-primary font-medium">{{ $course->category->name ?? 'General' }}</span>
        </div>

        {{-- Title --}}
        <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-primary transition-colors">
            <span class="cursor-pointer" onclick="viewCourseDetails({{ $course->id }})">{{ $course->title }}</span>
        </h3>

        {{-- Description --}}
        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $course->description }}</p>

        {{-- Progress Bar (if enabled) --}}
        @if($showProgress)
            <div class="mb-3">
                <div class="flex justify-between text-xs text-gray-600 mb-1">
                    <span>Progress</span>
                    <span>{{ $progress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $progress }}%"></div>
                </div>
            </div>
        @endif

        {{-- Stats Row --}}
        <div class="flex items-center gap-4 text-xs text-gray-500 mb-4">
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ $course->duration_text }}</span>
            </div>
            
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0a4.002 4.002 0 01-8 0"/>
                </svg>
                <span>{{ $course->enrolled_count ?? 0 }} students</span>
            </div>

            @if($course->average_rating > 0)
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.95-.69l1.07-3.292z"/>
                    </svg>
                    <span>{{ number_format($course->average_rating, 1) }}</span>
                </div>
            @endif
        </div>

        {{-- Author --}}
        <div class="flex items-center gap-2 mb-4">
            <img 
                src="{{ $course->creator->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($course->creator->name) }}"
                alt="{{ $course->creator->name }}"
                class="w-8 h-8 rounded-full object-cover"
            >
            <span class="text-sm text-gray-600">{{ $course->creator->name }}</span>
        </div>

        {{-- Action Button --}}
        @if($showEnrollButton)
            <div class="flex items-center justify-between">
                <div class="text-primary font-bold">
                    {{ $course->formatted_price }}
                </div>
                <button 
                    onclick="viewCourseDetails({{ $course->id }})"
                    class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primaryDark transition-colors text-sm font-medium"
                >
                    View Details
                </button>
            </div>
        @endif
    </div>
</article>