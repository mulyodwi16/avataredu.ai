@props([
    'title',
    'subtitle' => '',
    'actions' => null
])

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">{{ $title }}</h2>
        @if($subtitle)
            <p class="text-gray-500 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    
    @if($actions)
        <div class="flex items-center gap-3">
            {{ $actions }}
        </div>
    @endif
</div>