@php
  // default safe
  $title = $course['title'] ?? 'Untitled';
  $category = $course['category'] ?? '-';
  $description = $course['description'] ?? '';
  $image = $course['image'] ?? 'https://picsum.photos/seed/course/400/250';
  $price = $course['price'] ?? 0;
  $duration = $course['duration'] ?? '-';
  $level = $course['level'] ?? '-';
  $rating = $course['rating'] ?? 0;
  $reviews = $course['reviews'] ?? 0;
  $badge = $course['badge'] ?? null;
@endphp

<div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
  <div class="relative">
    @if($badge)
      <span class="absolute top-4 left-4 bg-orange-500 text-white text-sm px-3 py-1.5 rounded-lg font-medium">{{ $badge }}</span>
    @endif
    <span class="absolute top-4 right-4 bg-blue-600 text-white text-sm px-3 py-1.5 rounded-lg font-medium">
      Rp{{ number_format($price, 0, ',', '.') }}
    </span>
    <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-48 object-cover">
  </div>

  <div class="p-6">
    <div class="mb-4">
      <span class="text-sm text-gray-500 mb-2 block">{{ $category }}</span>
      @include('partials.star-rating', ['rating' => $rating, 'reviews' => $reviews])
    </div>

    <h3 class="font-semibold text-xl mb-3 text-gray-900">{{ $title }}</h3>
    <p class="text-gray-600 mb-6">{{ $description }}</p>

    <div class="flex items-center gap-3 text-gray-500 text-sm mb-6">
      <div class="flex items-center gap-1.5">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ $duration }}</span>
      </div>
      <div class="flex items-center gap-1.5">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
        </svg>
        <span>{{ $level }}</span>
      </div>
    </div>

    <div class="flex gap-3">
      <a href="#"
         class="flex-1 text-center py-2 border border-gray-300 rounded-lg text-gray-600 font-medium hover:bg-gray-50">
        View Details
      </a>
      <a href="#"
         class="flex-1 text-center py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
        Buy Now
      </a>
    </div>
  </div>
</div>