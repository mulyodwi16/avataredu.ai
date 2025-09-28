{{-- Top Bar --}}
<header class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b border-gray-100">
    <div class="mx-auto max-w-7xl px-4">
        <div class="h-16 flex items-center justify-between gap-4">
            {{-- Left: Brand + burger (mobile) --}}
            <div class="flex items-center gap-3">
                <button id="dashBurger" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="h-9 w-9 grid place-items-center rounded-xl bg-primary text-white font-black">A</div>
                    <span class="hidden sm:inline text-lg font-bold text-primary">Avataredu.ai</span>
                </a>
            </div>

            {{-- Middle: Search bar --}}
            <div class="flex-1 max-w-3xl">
                <label class="relative block">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.386a1 1 0 01-1.414 1.415l-4.387-4.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input type="text" placeholder="Search..." class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-200 bg-white/70 focus:bg-white
                                  focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
                </label>
            </div>

            {{-- Right: Cart + User --}}
            <div class="flex items-center gap-3">
                <a href="#" class="relative inline-flex items-center justify-center p-2 rounded-full hover:bg-gray-100">
                    <svg class="w-6 h-6 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.3 5.2A1 1 0 006.7 20h10.6a1 1 0 00.97-.76L20 13M7 13H5.4" />
                    </svg>
                    <span
                        class="absolute -top-0.5 -right-0.5 text-[10px] bg-accent text-white rounded-full px-1">0</span>
                </a>

                <div class="hidden sm:flex items-center gap-2 px-2 py-1 rounded-full bg-primary/10">
                    <div class="w-8 h-8 rounded-full bg-primary/20 overflow-hidden">
                        <img src="https://i.pravatar.cc/64?img=5" alt="avatar" class="w-full h-full object-cover">
                    </div>
                    <div class="leading-tight">
                        <div class="text-sm font-semibold text-primary">
                            {{ Str::limit(auth()->user()->name ?? 'User', 16) }}
                        </div>
                        <div class="text-[11px] text-gray-500">Student</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>