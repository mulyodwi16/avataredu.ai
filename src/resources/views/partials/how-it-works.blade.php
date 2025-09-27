<section class="py-20 bg-gradient-to-b from-white to-blue-50">
  <div class="max-w-7xl mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-2">How It Works</h2>
    <p class="text-gray-600 text-center mb-12">Start learning in just 4 simple steps</p>

    @php
      $steps = [
        ['icon' => 'search',      'title' => 'Choose a module',         'desc' => 'Browse our library of short, focused courses'],
        ['icon' => 'credit-card', 'title' => 'Purchase',                 'desc' => 'Secure payment with instant access'],
        ['icon' => 'book-open',   'title' => 'Learn at your own pace',   'desc' => 'Study anytime, anywhere on any device'],
        ['icon' => 'award',       'title' => 'Get a certificate',        'desc' => 'Earn completion certificates to showcase your skills'],
      ];

      // path ikon
      $iconPath = [
        'search'      => 'M21 21l-4.35-4.35M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z',
        'credit-card' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
        'book-open'   => 'M12 6.253v13M12 6.253C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13M12 6.253C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13',
        'award'       => 'M9 12l2 2 4-4M6 20l6-3 6 3V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14z',
      ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      @foreach ($steps as $i => $s)
        <div class="how-it-works-step text-center group">
          {{-- ikon + highlight hover --}}
          <div class="relative flex items-center justify-center mb-4">
            {{-- highlight peach di belakang ikon (muncul saat hover) --}}
            <span class="absolute w-40 h-20 rounded-lg bg-amber-200/80 -z-10 opacity-0 translate-y-2
                         transition duration-300 ease-out
                         group-hover:opacity-100 group-hover:translate-y-0"></span>

            {{-- ikon bulat biru --}}
            <div class="step-icon w-16 h-16 rounded-full text-white flex items-center justify-center mx-auto
                        bg-gradient-to-br from-blue-500 to-sky-600 shadow-md
                        transition-transform duration-300 group-hover:scale-105">
              <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath[$s['icon']] ?? $iconPath['search'] }}"/>
              </svg>
            </div>
          </div>

          {{-- judul + deskripsi --}}
          <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $s['title'] }}</h3>
          <p class="text-gray-600">{{ $s['desc'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
