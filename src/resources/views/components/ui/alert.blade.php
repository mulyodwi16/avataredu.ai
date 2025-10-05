@props([
    'message',
    'type' => 'info', // info, success, warning, error
    'dismissible' => true
])

@php
    $alertClasses = [
        'info' => 'bg-blue-50 border-blue-200 text-blue-700',
        'success' => 'bg-green-50 border-green-200 text-green-700',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-700', 
        'error' => 'bg-red-50 border-red-200 text-red-700'
    ];
    
    $iconPaths = [
        'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z',
        'error' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'
    ];
    
    $safeType = is_string($type) ? $type : 'info';
    $alertClass = $alertClasses[$safeType] ?? $alertClasses['info'];
    $iconPath = $iconPaths[$safeType] ?? $iconPaths['info'];
@endphp

<div class="rounded-lg border p-4 {{ $alertClass }}" role="alert">
    <div class="flex items-start">
        <svg class="w-5 h-5 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/>
        </svg>
        
        <div class="flex-1">
            {{ $message ?? $slot }}
        </div>
        
        @if($dismissible)
            <button 
                type="button" 
                class="ml-3 -mr-1 flex-shrink-0 p-1 hover:opacity-70 transition-opacity"
                onclick="this.parentElement.parentElement.remove()"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        @endif
    </div>
</div>