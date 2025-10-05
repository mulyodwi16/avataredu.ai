@props([
    'type' => 'button',
    'size' => 'md',
    'variant' => 'primary',
    'disabled' => false,
    'loading' => false,
    'href' => null
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm rounded-md',
        'md' => 'px-4 py-2 text-sm rounded-lg', 
        'lg' => 'px-6 py-3 text-base rounded-lg',
        'xl' => 'px-8 py-4 text-lg rounded-xl'
    ];
    
    $variantClasses = [
        'primary' => 'bg-primary text-white hover:bg-primaryDark focus:ring-primary/50',
        'secondary' => 'bg-gray-100 text-gray-900 hover:bg-gray-200 focus:ring-gray-500/50',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500/50',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500/50',
        'warning' => 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-500/50',
        'outline' => 'border-2 border-primary text-primary hover:bg-primary hover:text-white focus:ring-primary/50',
        'ghost' => 'text-primary hover:bg-primary/10 focus:ring-primary/50'
    ];
    
    $safeSize = is_string($size) ? $size : 'md';
    $safeVariant = is_string($variant) ? $variant : 'primary';
    $sizeClass = $sizeClasses[$safeSize] ?? $sizeClasses['md'];
    $variantClass = $variantClasses[$safeVariant] ?? $variantClasses['primary'];
    $classes = $baseClasses . ' ' . $sizeClass . ' ' . $variantClass;
@endphp

@if($href)
    <a 
        href="{{ $href }}" 
        class="{{ $classes }}"
        {{ $attributes }}
    >
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @endif
        {{ $slot }}
    </a>
@else
    <button 
        type="{{ $type }}"
        class="{{ $classes }}"
        @disabled($disabled || $loading)
        {{ $attributes }}
    >
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @endif
        {{ $slot }}
    </button>
@endif