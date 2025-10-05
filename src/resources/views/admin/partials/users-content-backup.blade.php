{{-- Manage Users Content --}}
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Manage Users</h2>
            <div class="flex items-center gap-4">
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">All Roles</option>
                    <option value="user">Users</option>
                    <option value="admin">Admins</option>
                </select>
                <input type="search" placeholder="Search users..."
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>
    </div>

    <div class="p-6">
        @if($users && $users->count() > 0)
            <div class="space-y-4">
                @foreach($users as $user)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <!-- User Info -->
                            <div class="flex items-center space-x-4">
                                <!-- Avatar -->
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                        class="w-12 h-12 rounded-full border-2 border-gray-200">
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                
                                <!-- Details -->
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-lg">{{ $user->name }}</h3>
                                    <p class="text-gray-600 text-sm">{{ $user->email }}</p>
                                    
                                    <!-- Role Badge -->
                                    @php
                                        $roleClasses = [
                                            'admin' => 'bg-rose-100 text-rose-800',
                                            'super_admin' => 'bg-violet-100 text-violet-800',
                                            'user' => 'bg-emerald-100 text-emerald-800'
                                        ];
                                        $roleClass = $roleClasses[$user->role] ?? $roleClasses['user'];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 mt-1 rounded-full text-xs font-medium {{ $roleClass }}">
                                        @if($user->role === 'admin')
                                            🛡️ Admin
                                        @elseif($user->role === 'super_admin')  
                                            👑 Super Admin
                                        @else
                                            👤 User
                                        @endif
                                    </span>
                                    
                                    <p class="text-xs text-gray-500 mt-1">
                                        📅 Joined {{ $user->created_at->format('M Y') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Actions - Only Delete and Make Admin -->
                            <div class="flex items-center gap-3">
                                @if($user->id !== auth()->id() && $user->role !== 'super_admin')
                                    @if($user->role !== 'admin')
                                        <button onclick="makeAdmin({{ $user->id }})"
                                            class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg text-sm font-medium transition-colors">
                                            Make Admin
                                        </button>
                                    @endif
                                    
                                    <button onclick="deleteUser({{ $user->id }})"
                                        class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg text-sm font-medium transition-colors">
                                        Delete
                                    </button>
                                @else
                                    <span class="text-gray-400 text-sm italic">
                                        @if($user->id === auth()->id()) (You) @else (Protected) @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination - Only if users is a paginated collection --}}
            @if(method_exists($users, 'hasPages') && $users->hasPages())
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="text-gray-500 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Users Found</h3>
                <p class="text-gray-600">No users match the current filters.</p>
            </div>
        @endif
    </div>
</div>
                                        @if($user->role === 'admin' || $user->role === 'superadmin')
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3h4v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                        @elseif($user->role === 'creator')
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        @else
                                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                        @endif
                                    </svg>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>

                            <!-- User Stats -->
                            <div class="text-xs text-gray-500 mb-4">
                                <div class="flex items-center justify-center gap-4">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $user->created_at->format('M Y') }}
                                    </span>
                                    @if($user->role === 'creator')
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                            </svg>
                                            {{ $user->courses_count ?? 0 }} courses
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <button class="flex-1 text-xs px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                                    View Profile
                                </button>
                                @if($user->id !== auth()->id())
                                    <button class="flex-1 text-xs px-3 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg font-medium transition-colors">
                                        Manage
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="text-gray-500 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Users Found</h3>
                <p class="text-gray-600">No users match the current filters.</p>
            </div>
        @endif
    </div>
</div>