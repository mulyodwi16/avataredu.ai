@ext@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary/5 to-white">
    <div class="mx-auto max-w-7xl px-4 py-8">s('layouts.app')

@section('title', 'User Details')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-primary/5 to-white">
        <div class="mx-auto max-w-7xl px-4 py-8">
            {{-- Header --}}
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-4">
                    <a href="{{ route('admin.users.index') }}"
                        class="p-2 rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h1>
                        <p class="text-gray-600">User Details</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- User Profile Card --}}
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center gap-6 mb-6">
                            <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                                        class="w-20 h-20 rounded-full object-cover">
                                @else
                                    <span
                                        class="text-2xl text-primary font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                                <p class="text-gray-600">{{ $user->email }}</p>
                                <div class="flex items-center gap-4 mt-2">
                                    @if($user->role === 'admin')
                                        <span class="px-3 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded-full">
                                            Administrator
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">
                                            User
                                        </span>
                                    @endif

                                    @if($user->email_verified_at)
                                        <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">
                                            Verified
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">
                                            Unverified
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Full Name</label>
                                <p class="text-gray-900">{{ $user->name }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">Email Address</label>
                                <p class="text-gray-900">{{ $user->email }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">Role</label>
                                <p class="text-gray-900 capitalize">{{ $user->role }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">Account Status</label>
                                <p class="text-gray-900">
                                    {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                                </p>
                            </div>

                            @if($user->bio)
                                <div class="md:col-span-2">
                                    <label class="text-sm font-medium text-gray-600">Bio</label>
                                    <p class="text-gray-900">{{ $user->bio }}</p>
                                </div>
                            @endif

                            @if($user->website)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Website</label>
                                    <p class="text-gray-900">
                                        <a href="{{ $user->website }}" target="_blank" class="text-primary hover:underline">
                                            {{ $user->website }}
                                        </a>
                                    </p>
                                </div>
                            @endif

                            @if($user->expertise)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Expertise</label>
                                    <p class="text-gray-900">{{ $user->expertise }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- User Activity --}}
                    @if($user->role === 'admin')
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Created Courses</h3>

                            @if($userCourses->count() > 0)
                                <div class="space-y-4">
                                    @foreach($userCourses as $course)
                                        <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg">
                                            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}"
                                                class="w-16 h-12 object-cover rounded-lg">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900">{{ $course->title }}</h4>
                                                <p class="text-sm text-gray-500">{{ $course->category->name ?? 'N/A' }} •
                                                    {{ $course->enrolled_count ?? 0 }} students</p>
                                            </div>
                                            <div class="text-right">
                                                @if($course->is_published)
                                                    <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">
                                                        Published
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
                                                        Draft
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No courses created yet.</p>
                            @endif
                        </div>
                    @else
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Enrolled Courses</h3>

                            @if($userCourses->count() > 0)
                                <div class="space-y-4">
                                    @foreach($userCourses as $enrollment)
                                        <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg">
                                            <img src="{{ $enrollment->course->thumbnail_url }}" alt="{{ $enrollment->course->title }}"
                                                class="w-16 h-12 object-cover rounded-lg">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900">{{ $enrollment->course->title }}</h4>
                                                <p class="text-sm text-gray-500">Progress: {{ $enrollment->progress_percentage ?? 0 }}%
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                @if($enrollment->completed_at)
                                                    <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">
                                                        Completed
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">
                                                        In Progress
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No courses enrolled yet.</p>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Account Info --}}
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Information</h3>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Member Since</span>
                                <span class="text-gray-900">{{ $user->created_at->format('M j, Y') }}</span>
                            </div>

                            @if($user->email_verified_at)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Verified On</span>
                                    <span class="text-gray-900">{{ $user->email_verified_at->format('M j, Y') }}</span>
                                </div>
                            @endif

                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Last Updated</span>
                                <span class="text-gray-900">{{ $user->updated_at->format('M j, Y') }}</span>
                            </div>

                            @if($user->provider)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Login Provider</span>
                                    <span class="text-gray-900 capitalize">{{ $user->provider }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    @if($user->id !== auth()->id())
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>

                            <div class="space-y-3">
                                {{-- Change Role --}}
                                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="role" value="{{ $user->role === 'admin' ? 'user' : 'admin' }}">
                                    <button type="submit"
                                        class="w-full px-4 py-2 {{ $user->role === 'admin' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-purple-600 hover:bg-purple-700' }} text-white rounded-lg transition-colors"
                                        onclick="return confirm('Are you sure you want to change this user\'s role?')">
                                        {{ $user->role === 'admin' ? 'Make Regular User' : 'Make Admin' }}
                                    </button>
                                </form>

                                {{-- Send Reset Link (if unverified) --}}
                                @if(!$user->email_verified_at)
                                    <button type="button"
                                        class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        Send Verification Email
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- User Statistics --}}
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistics</h3>

                        <div class="space-y-4">
                            @if($user->role === 'admin')
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Courses Created</span>
                                    <span class="text-xl font-bold text-primary">{{ $userCourses->count() }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Published Courses</span>
                                    <span
                                        class="text-xl font-bold text-green-600">{{ $userCourses->where('is_published', true)->count() }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Total Students</span>
                                    <span
                                        class="text-xl font-bold text-blue-600">{{ $userCourses->sum('enrolled_count') }}</span>
                                </div>
                            @else
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Enrolled Courses</span>
                                    <span class="text-xl font-bold text-primary">{{ $userCourses->count() }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Completed Courses</span>
                                    <span
                                        class="text-xl font-bold text-green-600">{{ $userCourses->whereNotNull('completed_at')->count() }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">In Progress</span>
                                    <span
                                        class="text-xl font-bold text-blue-600">{{ $userCourses->whereNull('completed_at')->count() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection