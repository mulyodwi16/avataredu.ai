{{-- Sidebar --}}
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
                    ['label' => 'Account', 'icon' => 'M5.121 17.804A8 8 0 1118.879 6.196', 'href' => route('account')],
                    ['label' => 'Course', 'icon' => 'M19 11H5m14 0l-7-7m7 7l-7 7', 'href' => route('courses')],
                    ['label' => 'Collection', 'icon' => 'M4 6h16M4 10h16M4 14h16', 'href' => route('collection')],
                    ['label' => 'Purchased History', 'icon' => 'M12 8v8m-4-4h8', 'href' => route('purchase-history')],
                    ['label' => 'Institution Learning', 'icon' => 'M12 3l8 4-8 4-8-4 8-4z', 'href' => route('institution')],
                ];
            @endphp

            @foreach ($items as $it)
                <a href="{{ $it['href'] }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-primary/5 text-gray-700 hover:text-primary
                              {{ request()->url() === $it['href'] ? 'bg-primary/5 text-primary' : '' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="{{ $it['icon'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
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

{{-- Tiny JS: toggle sidebar mobile --}}
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