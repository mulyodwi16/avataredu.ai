@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full mx-auto p-6">
            {{-- Logo --}}
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                    <div class="h-12 w-12 rounded-xl bg-primary grid place-items-center">
                        <span class="text-2xl font-black text-white">A</span>
                    </div>
                    <span class="text-2xl font-bold text-primary">Avataredu.ai</span>
                </a>
                <h1 class="mt-4 text-2xl font-bold text-gray-900">Reset Password</h1>
                <p class="mt-2 text-sm text-gray-600">
                    Create a new password for your account
                </p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                {{-- Password Reset Token --}}
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $request->email) }}" required
                        autofocus
                        class="mt-1 block w-full px-4 py-2 rounded-lg border {{ $errors->has('email') ? 'border-red-400 ring-2 ring-red-500/20' : 'border-slate-200' }} focus:ring-2 {{ $errors->has('email') ? 'focus:ring-red-500/20 focus:border-red-400' : 'focus:ring-primary/20 focus:border-primary' }} transition">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" name="password" id="password" required
                        class="mt-1 block w-full px-4 py-2 rounded-lg border {{ $errors->has('password') ? 'border-red-400 ring-2 ring-red-500/20' : 'border-slate-200' }} focus:ring-2 {{ $errors->has('password') ? 'focus:ring-red-500/20 focus:border-red-400' : 'focus:ring-primary/20 focus:border-primary' }} transition">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                        Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="mt-1 block w-full px-4 py-2 rounded-lg border {{ $errors->has('password_confirmation') ? 'border-red-400 ring-2 ring-red-500/20' : 'border-slate-200' }} focus:ring-2 {{ $errors->has('password_confirmation') ? 'focus:ring-red-500/20 focus:border-red-400' : 'focus:ring-primary/20 focus:border-primary' }} transition">
                </div>

                <button type="submit"
                    class="w-full px-4 py-2 rounded-lg bg-primary text-white font-semibold hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    Reset Password
                </button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm text-primary hover:underline">
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection