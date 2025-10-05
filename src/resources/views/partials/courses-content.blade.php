{{-- Course Filters --}}
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Search --}}
        <div>
            <input type="text" id="courseSearch" placeholder="Search courses..."
                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary"
                value="{{ $search ?? '' }}" onchange="searchCourses()">
        </div>

        {{-- Category Filter --}}
        <div>
            <select id="categoryFilter"
                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary"
                onchange="filterCourses()">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Sort Filter --}}
        <div>
            <select id="sortFilter"
                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary"
                onchange="sortCourses()">
                <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>Most Popular</option>
                <option value="price_low" {{ $sort == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_high" {{ $sort == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
            </select>
        </div>

        {{-- Apply Button --}}
        <div>
            <button onclick="applyCourseFilters()"
                class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90">
                Apply Filters
            </button>
        </div>
    </div>
</div>

{{-- Courses Grid --}}
<div id="coursesGrid">
    @if(isset($courses) && (is_array($courses) ? count($courses) > 0 : $courses->count() > 0))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
                    {{-- Course Thumbnail --}}
                    <div class="relative">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}"
                                class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-primary to-accent flex items-center justify-center">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        @endif

                        {{-- Price Badge --}}
                        <div class="absolute top-3 right-3">
                            @if($course->price > 0)
                                <span class="bg-white text-primary px-3 py-1 rounded-full text-sm font-semibold shadow">
                                    Rp {{ number_format($course->price, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="bg-accent text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    Free
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Course Info --}}
                    <div class="p-6">
                        {{-- Category --}}
                        @if($course->category)
                            <span class="inline-block bg-primary/10 text-primary text-xs px-2 py-1 rounded-full mb-2">
                                {{ $course->category->name }}
                            </span>
                        @endif

                        {{-- Title & Description --}}
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $course->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ Str::limit($course->description, 100) }}</p>

                        {{-- Course Meta --}}
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $course->students_count ?? 0 }} students
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $course->duration ?? 'N/A' }}
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <div class="flex gap-2">
                            <button onclick="viewCourseDetails({{ $course->id }})"
                                class="flex-1 bg-primary text-white text-center px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors">
                                View Course
                            </button>
                            @if($course->price == 0)
                                <button class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-accent/90 transition-colors">
                                    Enroll Free
                                </button>
                            @else
                                <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                                    Add to Cart
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if(is_object($courses) && method_exists($courses, 'links'))
            <div class="mt-8 flex justify-center">
                {{ $courses->links() }}
            </div>
        @endif
    @else
        {{-- No Courses Found --}}
        <div class="text-center py-12 bg-white rounded-xl">
            <div class="w-16 h-16 mx-auto mb-4 text-gray-300">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h4 class="text-lg font-medium text-gray-900 mb-2">No courses found</h4>
            <p class="text-gray-500 mb-4">Try adjusting your search or filter criteria</p>
            <button onclick="clearFilters()"
                class="inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90">
                Clear Filters
            </button>
        </div>
    @endif
</div>

<script>
    function applyCourseFilters() {
        const search = document.getElementById('courseSearch').value;
        const category = document.getElementById('categoryFilter').value;
        const sort = document.getElementById('sortFilter').value;

        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (category) params.append('category', category);
        if (sort) params.append('sort', sort);

        fetch(`/api/dashboard/courses?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.html) {
                    document.querySelector('[data-content]').innerHTML = data.html;
                }
            });
    }

    function clearFilters() {
        document.getElementById('courseSearch').value = '';
        document.getElementById('categoryFilter').value = '';
        document.getElementById('sortFilter').value = 'latest';
        applyCourseFilters();
    }
</script>