@props([
    'items' => [],
    'columns' => 'auto-fit',
    'minWidth' => '280px',
    'gap' => '1.5rem'
])

<div 
    class="grid gap-6"
    style="grid-template-columns: repeat({{ $columns }}, minmax({{ $minWidth }}, 1fr)); gap: {{ $gap }};"
    {{ $attributes->merge(['class' => '']) }}
>
    @if(!empty($items))
        @foreach($items as $item)
            {{ $item }}
        @endforeach
    @else
        {{ $slot }}
    @endif
</div>