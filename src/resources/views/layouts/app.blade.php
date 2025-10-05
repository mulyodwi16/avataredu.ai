<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Avataredu.ai')</title>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  {{-- Font Awesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  {{-- Page-specific head injections --}}
  @stack('head')

  {{-- Assets --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-poppins text-gray-800
    @if (request()->routeIs('login') || request()->routeIs('register'))
      bg-gradient-to-b from-primary to-white
    @else
      bg-white
    @endif
    @yield('body-class')">

  {{-- Navbar: tampil di semua halaman kecuali login/register dan halaman dashboard/creator --}}
  @unless (request()->routeIs('login') 
          || request()->routeIs('register') 
          || str_starts_with(request()->route()->getName(), 'dashboard')
          || str_starts_with(request()->route()->getName(), 'creator.')
          || request()->is('dashboard*'))
    @include('partials.navbar')
  @endunless

  <main>
    @yield('content')
  </main>

  {{-- Footer: sembunyikan di login/register dan halaman dashboard/creator --}}
  @unless (request()->routeIs('login') 
          || request()->routeIs('register')
          || str_starts_with(request()->route()->getName(), 'dashboard')
          || str_starts_with(request()->route()->getName(), 'creator.')
          || request()->is('dashboard*'))
    @include('partials.footer')
  @endunless

  {{-- Default scripts --}}
  @push('scripts')
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const btnMenu = document.getElementById("btnMenu");
        const mobileMenu = document.getElementById("mobileMenu");
        const iconOpen = document.getElementById("iconOpen");
        const iconClose = document.getElementById("iconClose");

        btnMenu?.addEventListener("click", () => {
          const willOpen = mobileMenu.classList.contains("hidden");
          mobileMenu.classList.toggle("hidden", !willOpen);
          iconOpen?.classList.toggle("hidden", !willOpen);
          iconClose?.classList.toggle("hidden", willOpen);
        });
      });
    </script>
  @endpush

  {{-- Page-specific scripts --}}
  @stack('scripts')
</body>

</html>