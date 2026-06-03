@props([
    'type' => 'info',
    'title' => null,
    'message' => '',
    'duration' => 5000,
    'show' => false
])

@php
    $typeClasses = [
        'success' => 'bg-green-500 text-white',
        'error' => 'bg-red-500 text-white',
        'warning' => 'bg-yellow-500 text-white',
        'info' => 'bg-blue-500 text-white',
        'overdue' => 'bg-red-600 text-white border-2 border-red-800'
    ];
    
    $iconClasses = [
        'success' => 'bi-check-circle-fill',
        'error' => 'bi-x-circle-fill',
        'warning' => 'bi-exclamation-triangle-fill',
        'info' => 'bi-info-circle-fill',
        'overdue' => 'bi-exclamation-octagon-fill'
    ];
    
    $class = $typeClasses[$type] ?? $typeClasses['info'];
    $icon = $iconClasses[$type] ?? $iconClasses['info'];
@endphp

<div x-data="{ show: {{ $show ? 'true' : 'false' }} }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="transform translate-x-full opacity-0"
     x-transition:enter-end="transform translate-x-0 opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="transform translate-x-0 opacity-100"
     x-transition:leave-end="transform translate-x-full opacity-0"
     class="fixed top-4 right-4 z-50 max-w-sm w-full">
    
    <div class="{{ $class }} rounded-lg shadow-lg p-4 flex items-start space-x-3">
        <i class="bi {{ $icon }} text-xl flex-shrink-0 mt-0.5"></i>
        <div class="flex-1 min-w-0">
            @if($title)
                <p class="font-semibold">{{ $title }}</p>
            @endif
            <p class="text-sm {{ $title ? 'mt-1' : '' }}">{{ $message }}</p>
        </div>
        <button @click="show = false" class="flex-shrink-0 ml-4">
            <i class="bi bi-x-lg text-xl"></i>
        </button>
    </div>
</div>
