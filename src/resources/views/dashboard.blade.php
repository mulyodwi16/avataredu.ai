@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- Background halaman --}}
    <div class="min-h-screen bg-gradient-to-br from-primary/10 via-white to-white">

        {{-- ====== Top Bar ====== --}}
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
                            <input type="text" placeholder="Search product..." class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-200 bg-white/70 focus:bg-white
                              focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
                        </label>
                    </div>

                    {{-- Right: Cart + User --}}
                    <div class="flex items-center gap-3">
                        <a href="#"
                            class="relative inline-flex items-center justify-center p-2 rounded-full hover:bg-gray-100">
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

        {{-- ====== Content Area ====== --}}
        <div class="mx-auto max-w-7xl px-4 py-6 lg:py-8 flex gap-6">

            {{-- ====== Sidebar ====== --}}
            <aside id="dashSidebar" class="fixed inset-y-16 left-0 w-72 translate-x-[-110%] lg:translate-x-0 lg:static
                      bg-white/80 backdrop-blur border-r border-gray-100 shadow lg:shadow-none
                      z-30 transition-transform duration-300">
                <div class="p-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-6">
                        <div class="h-9 w-9 grid place-items-center rounded-xl bg-primary text-white font-black">A</div>
                        <span class="text-lg font-bold text-primary">Avataredu.ai</span>
                    </a>

                    <nav class="space-y-1">
                        @php
                            $items = [
                                ['label' => 'Home', 'icon' => 'M3 12h18', 'href' => route('dashboard')],
                                ['label' => 'Account', 'icon' => 'M5.121 17.804A8 8 0 1118.879 6.196', 'href' => '#'],
                                ['label' => 'Course', 'icon' => 'M19 11H5m14 0l-7-7m7 7l-7 7', 'href' => '#'],
                                ['label' => 'Collection', 'icon' => 'M4 6h16M4 10h16M4 14h16', 'href' => '#'],
                                ['label' => 'Purchased History', 'icon' => 'M12 8v8m-4-4h8', 'href' => '#'],
                                ['label' => 'Institution Learning', 'icon' => 'M12 3l8 4-8 4-8-4 8-4z', 'href' => '#'],
                            ];
                          @endphp

                        @foreach ($items as $it)
                            <a href="{{ $it['href'] }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl
                              hover:bg-primary/5 text-gray-700 hover:text-primary">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="{{ $it['icon'] }}" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span class="font-medium">{{ $it['label'] }}</span>
                            </a>
                        @endforeach

                        <form action="{{ route('logout') }}" method="POST" class="pt-2">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-left
                               hover:bg-red-50 text-gray-700 hover:text-red-600">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0V7a2 2 0 114 0v1" />
                                </svg>
                                <span class="font-medium">Logout</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </aside>

            {{-- ====== Main ====== --}}
            <main class="flex-1 space-y-8">

                {{-- Hero Banner --}}
                <section class="bg-night/90 text-white rounded-2xl shadow overflow-hidden relative">
                    <div class="grid md:grid-cols-[1fr,1.3fr]">
                        <div class="p-8 md:p-10">
                            <h2 class="text-2xl md:text-3xl font-extrabold mb-3">
                                Unlock Your Career Potential with Kirana!
                            </h2>
                            <p class="text-white/80 mb-6">
                                Explore a career path tailored to your skills and ambitions. Get a personalized roadmap and
                                course suggestions to achieve your goals.
                            </p>
                            <a href="#"
                                class="inline-block bg-accent text-white font-semibold px-5 py-2 rounded-xl hover:opacity-90">
                                Click here to start!
                            </a>
                        </div>
                        <div class="relative p-6 md:p-8">
                            <img src="https://via.placeholder.com/520x260.png?text=Hero+Banner"
                                class="w-full h-56 md:h-full object-cover rounded-xl" alt="hero">
                            {{-- decorative --}}
                            <div
                                class="hidden md:block absolute -right-6 -bottom-6 w-40 h-40 rounded-full bg-accent/30 blur-2xl">
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Explore other courses --}}
                <section>
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-800">Explore other courses</h3>
                        <a href="#" class="text-primary hover:underline text-sm font-medium">View all</a>
                    </div>

                    {{-- scrollable on mobile, grid on desktop --}}
                    <div class="flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2
                        md:grid md:grid-cols-3 xl:grid-cols-4 md:overflow-visible md:snap-none">
                        @php
                            $courses = [
                                ['title' => 'Perencanaan Smart Inverter Sistem Terintegrasi EBT Untuk Ketenagalistrikan', 'price' => 30000, 'badge' => 'Sold', 'level' => 'Specialized', 'chap' => '1 Chapter', 'img' => 'https://via.placeholder.com/480x300.png?text=Course+1'],
                                ['title' => 'Global Sustainable Finance & Emerging Trends', 'price' => 25000, 'badge' => 'Sold', 'level' => 'Advanced', 'chap' => '2 Chapters', 'img' => 'https://via.placeholder.com/480x300.png?text=Course+2'],
                                ['title' => 'Matematika Kelas III SD', 'price' => 25000, 'badge' => '3 Sold', 'level' => 'ES', 'chap' => '5 Chapters', 'img' => 'https://via.placeholder.com/480x300.png?text=Course+3'],
                                ['title' => 'AI in Education', 'price' => 25000, 'badge' => '5 Sold', 'level' => 'Beginner', 'chap' => '3 Chapters', 'img' => 'https://via.placeholder.com/480x300.png?text=Course+4'],
                                ['title' => 'Startup Pitching', 'price' => 25000, 'badge' => '2 Sold', 'level' => 'Intermediate', 'chap' => '6 Chapters', 'img' => 'https://via.placeholder.com/480x300.png?text=Course+5'],
                            ];
                          @endphp

                        @foreach ($courses as $c)
                            <article class="bg-white rounded-2xl shadow hover:shadow-lg transition
                                    min-w-[280px] snap-start md:min-w-0 overflow-hidden">
                                <div class="relative">
                                    <img src="{{ $c['img'] }}" alt="cover" class="w-full h-44 object-cover">
                                    <span
                                        class="absolute top-2 left-2 text-[11px] px-2 py-0.5 rounded-full bg-gray-900/80 text-white">
                                        {{ $c['badge'] }}
                                    </span>
                                </div>
                                <div class="p-4">
                                    <span
                                        class="inline-block text-[11px] px-2 py-0.5 rounded-full bg-primary/10 text-primary font-medium">
                                        Academic
                                    </span>
                                    <h4 class="mt-2 font-semibold line-clamp-2 min-h-[48px]">{{ $c['title'] }}</h4>
                                    <p class="mt-1 text-gray-500 text-sm">eJourney • Last modified:
                                        {{ now()->subDays(rand(1, 20))->format('d/m/Y') }}</p>

                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                        <span
                                            class="text-[11px] px-2 py-0.5 rounded-md bg-accent/10 text-accent font-medium">{{ $c['level'] }}</span>
                                        <span
                                            class="text-[11px] px-2 py-0.5 rounded-md bg-primary/10 text-primary font-medium">{{ $c['chap'] }}</span>
                                    </div>

                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="text-primary font-bold">
                                            {{ number_format($c['price'], 0, ',', '.') }}
                                        </div>
                                        <a href="#"
                                            class="text-sm px-3 py-1.5 rounded-lg bg-primary text-white hover:bg-primaryDark">
                                            Details
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>

            </main>
        </div>
    </div>

    {{-- ====== Tiny JS: toggle sidebar mobile ====== --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('dashBurger');
            const side = document.getElementById('dashSidebar');
            btn?.addEventListener('click', () => {
                const hidden = side.classList.contains('translate-x-[-110%]');
                side.classList.toggle('translate-x-[-110%]', !hidden);
                side.classList.toggle('translate-x-0', hidden);
            });
        });
    </script>
@endsection