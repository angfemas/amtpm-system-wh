@props([
    'title' => null,
    'padding' => 'p-6',
    'class' => '',
])

<div class="industrial-card {{ $padding }} {{ $class }}">
    @if($title)
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            @isset($header)
                <div>{{ $header }}</div>
            @endisset
        </div>
    @endif
    
    {{ $slot }}
</div>
