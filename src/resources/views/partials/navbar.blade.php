<header class="bg-white border-b border-gray-200 sticky top-0 z-50">
  <div class="mx-auto max-w-7xl px-4">
    <div class="flex h-16 items-center justify-between">

      {{-- Logo --}}
      <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center gap-2">
        <span class="text-xl font-bold">avataredu.ai</span>
      </a>

      {{-- Desktop nav --}}
      <nav class="hidden lg:flex items-center lg:gap-8">
        <a href="{{ route('dashboard') }}"
          class="text-gray-600 hover:text-blue-600 {{ request()->routeIs('dashboard*') ? 'text-blue-600 font-semibold' : '' }}">
          Courses
        </a>

        @auth
          <a href="{{ route('dashboard') }}"
            class="text-gray-600 hover:text-blue-600 {{ request()->routeIs('dashboard') ? 'text-blue-600 font-semibold' : '' }}">
            My Collection
          </a>
          <a href="{{ route('dashboard') }}" onclick="navigateToPurchaseHistory(event)"
            class="text-gray-600 hover:text-blue-600 {{ request()->routeIs('dashboard') ? 'text-blue-600 font-semibold' : '' }}">
            Purchase History
          </a>
          <a href="{{ route('dashboard') }}"
            class="text-gray-600 hover:text-blue-600 {{ request()->routeIs('dashboard') ? 'text-blue-600 font-semibold' : '' }}">
            Dashboard
          </a>
        @else
          <a href="#" class="text-gray-600 hover:text-blue-600">About</a>
          <a href="#" class="text-gray-600 hover:text-blue-600">Help</a>
        @endauth
      </nav>

      {{-- Desktop actions --}}
      <div class="hidden lg:flex items-center gap-3">
        @auth
          <span class="text-gray-700">Hi, {{ auth()->user()->name }}</span>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 rounded-xl bg-primary text-white font-semibold hover:bg-primaryDark">
              Logout
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary font-medium">Sign in</a>
          <a href="{{ route('register') }}"
            class="bg-primary text-white px-4 py-2 rounded-xl font-semibold hover:bg-primaryDark">
            Sign up
          </a>
        @endauth
      </div>

      {{-- Burger button (mobile) --}}
      <button id="btnMenu" class="lg:hidden inline-flex items-center justify-center rounded-lg p-2 hover:bg-gray-100"
        aria-controls="mobileMenu" aria-expanded="false" aria-label="Open main menu">
        <svg id="iconOpen" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg id="iconClose" class="h-6 w-6 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>

  {{-- Mobile menu --}}
  <div id="mobileMenu" class="hidden lg:hidden border-t border-gray-100">
    <div class="space-y-1 px-4 py-3">
      <a href="{{ route('dashboard') }}"
        class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard*') ? 'bg-primary/10 text-primary' : '' }}">
        Courses
      </a>

      @auth
        <a href="{{ route('dashboard') }}"
          class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : '' }}">
          My Collection
        </a>
        <a href="{{ route('dashboard') }}" onclick="navigateToPurchaseHistory(event)"
          class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : '' }}">
          Purchase History
        </a>
        <a href="{{ route('dashboard') }}"
          class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : '' }}">
          Dashboard
        </a>
        <form action="{{ route('logout') }}" method="POST" class="pt-2">
          @csrf
          <button type="submit"
            class="w-full text-center rounded-xl px-4 py-2 bg-primary text-white font-semibold hover:bg-primaryDark">
            Logout
          </button>
        </form>
      @else
        <a href="#" class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100">About</a>
        <a href="#" class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100">Help</a>

        <div class="pt-2 flex flex-col gap-2">
          <a href="{{ route('login') }}"
            class="w-full text-center rounded-xl px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50">
            Sign in
          </a>
          <a href="{{ route('register') }}"
            class="w-full text-center rounded-xl px-4 py-2 bg-primary text-white font-semibold hover:bg-primaryDark">
            Sign up
          </a>
        </div>
      @endauth
    </div>
  </div>
</header>

<script>
  function navigateToPurchaseHistory(event) {
    // If we're already on the dashboard page, prevent default and use JavaScript navigation
    if (window.location.pathname.includes('/dashboard') && typeof loadPage === 'function') {
      event.preventDefault();
      loadPage('purchase-history');
    }
    // Otherwise, let the normal link behavior happen (will redirect to dashboard)
  }
</script>