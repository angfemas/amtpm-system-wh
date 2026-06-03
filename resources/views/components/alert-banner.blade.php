@props([
    'type' => 'info',
    'message' => '',
    'dismissible' => true,
    'icon' => null
])

@php
    $typeClasses = [
        'success' => 'bg-green-50 border-green-200 text-green-800',
        'error' => 'bg-red-50 border-red-200 text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
        'overdue' => 'bg-red-100 border-red-300 text-red-900'
    ];
    
    $iconClasses = [
        'success' => 'bi-check-circle-fill text-green-600',
        'error' => 'bi-x-circle-fill text-red-600',
        'warning' => 'bi-exclamation-triangle-fill text-yellow-600',
        'info' => 'bi-info-circle-fill text-blue-600',
        'overdue' => 'bi-exclamation-octagon-fill text-red-700'
    ];
    
    $class = $typeClasses[$type] ?? $typeClasses['info'];
    $defaultIcon = $iconClasses[$type] ?? $iconClasses['info'];
@endphp

<div x-data="{ show: true }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform -translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform -translate-y-2"
     class="border rounded-lg p-4 {{ $class }}">
    
    <div class="flex items-start">
        <i class="bi {{ $icon ?? $defaultIcon }} text-xl mr-3 flex-shrink-0 mt-0.5"></i>
        <div class="flex-1">
            <p class="text-sm font-medium">{{ $message }}</p>
            {{ $slot }}
        </div>
        
        @if($dismissible)
            <button @click="show = false" class="ml-4 flex-shrink-0">
                <i class="bi bi-x-lg text-xl opacity-60 hover:opacity-100"></i>
            </button>
        @endif
    </div>
</div>
