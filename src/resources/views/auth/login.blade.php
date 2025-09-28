@extends('layouts.app')

@section('title', 'Login')

@section('content')
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary/10 via-white to-white px-4">
    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-xl overflow-hidden grid md:grid-cols-2">

      {{-- Form kiri --}}
      <div class="p-8 md:p-12 flex flex-col justify-center">
        <h2 class="text-3xl font-bold text-primary mb-6">Welcome Back</h2>
        <p class="text-gray-600 mb-6">Login to access your dashboard and continue your learning journey.</p>

        {{-- Alert error (aktif & aman, tidak dalam komentar HTML) --}}
        @if ($errors->any())
          <div class="mb-4 rounded-lg border border-red-200 bg-red-50 text-red-700 px-4 py-3 text-sm">
            @foreach ($errors->all() as $err)
              <div>{{ $err }}</div>
            @endforeach
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
          @csrf

          <input type="email" name="email" value="{{ old('email') }}" placeholder="Email"
            class="w-full px-4 py-2 rounded-lg border {{ $errors->has('email') ? 'border-red-400 ring-2 ring-red-500/20' : 'border-slate-200' }} focus:ring-2 {{ $errors->has('email') ? 'focus:ring-red-500/20 focus:border-red-400' : 'focus:ring-primary/20 focus:border-primary' }} transition"
            required autofocus>

          <input type="password" name="password" placeholder="Password"
            class="w-full px-4 py-2 rounded-lg border {{ $errors->has('password') ? 'border-red-400 ring-2 ring-red-500/20' : 'border-slate-200' }} focus:ring-2 {{ $errors->has('password') ? 'focus:ring-red-500/20 focus:border-red-400' : 'focus:ring-primary/20 focus:border-primary' }} transition"
            required>

          <div class="text-right text-sm">
            <a href="{{ route('password.request') }}" class="text-primary hover:underline">Forgot password?</a>
          </div>

          <button type="submit" class="w-full bg-primary hover:bg-primaryDark text-white font-semibold py-2 rounded-lg">
            Sign In
          </button>
        </form>

        {{-- Divider --}}
        <div class="relative my-6">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Or continue with</span>
          </div>
        </div>

        {{-- Google Login Button --}}
        <a href="{{ route('google.redirect') }}"
          class="flex items-center justify-center gap-2 w-full border border-gray-300 rounded-lg py-2 hover:bg-gray-50 transition duration-150">
          <svg class="w-5 h-5" viewBox="0 0 24 24" aria-hidden="true">
            <path fill="#4285F4"
              d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
            <path fill="#34A853"
              d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
            <path fill="#FBBC05"
              d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
            <path fill="#EA4335"
              d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
          </svg>
          <span class="text-gray-700 font-medium">Sign in with Google</span>
        </a>

        <p class="mt-6 text-sm text-gray-600">
          Don’t have an account?
          <a href="{{ route('register') }}" class="text-primary font-medium hover:underline">Sign up</a>
        </p>
      </div>

      {{-- Ilustrasi kanan --}}
      <div class="hidden md:flex items-center justify-center bg-gradient-to-br from-primary to-accent">
        <img src="https://via.placeholder.com/400x400.png?text=Login+Illustration" alt="Login illustration"
          class="max-h-80">
      </div>
    </div>
  </div>
@endsection