@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'icon' => null,
    'disabled' => false,
    'fullWidth' => false,
    'loading' => false,
    'class' => ''
])

@php
$variantClasses = [
    'primary' => 'bg-red-600 hover:bg-red-700 text-white shadow-sm hover:shadow-md',
    'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-700',
    'success' => 'bg-green-600 hover:bg-green-700 text-white shadow-sm hover:shadow-md',
    'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-sm hover:shadow-md',
    'outline' => 'border border-gray-300 bg-white hover:bg-gray-50 text-gray-700',
    'ghost' => 'text-gray-700 hover:bg-gray-100',
];

$sizeClasses = [
    'xs' => 'px-2 py-1 text-xs',
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
    'xl' => 'px-8 py-4 text-lg',
];

$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';
$disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';
$widthClasses = $fullWidth ? 'w-full' : '';
$ringClasses = [
    'primary' => 'focus:ring-red-500',
    'secondary' => 'focus:ring-gray-500',
    'success' => 'focus:ring-green-500',
    'warning' => 'focus:ring-yellow-500',
    'danger' => 'focus:ring-red-500',
    'outline' => 'focus:ring-gray-500',
    'ghost' => 'focus:ring-gray-500',
];

$classes = implode(' ', [
    $baseClasses,
    $variantClasses[$variant] ?? $variantClasses['primary'],
    $sizeClasses[$size] ?? $sizeClasses['md'],
    $ringClasses[$variant] ?? $ringClasses['primary'],
    $disabledClasses,
    $widthClasses,
    $class
]);
@endphp

@if($href && $type === 'button')
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} {{ $disabled ? 'tabindex="-1"' : '' }}>
        @if($loading)
            <i class="bi bi-arrow-repeat animate-spin mr-2"></i>
        @endif
        @if($icon && !$loading)
            <i class="bi bi-{{ $icon }} mr-2"></i>
        @endif
        {{ $slot }}
    </a>
@elseif($type === 'submit')
    <button type="submit" {{ $attributes->merge(['class' => $classes]) }} {{ $disabled ? 'disabled' : '' }}>
        @if($loading)
            <i class="bi bi-arrow-repeat animate-spin mr-2"></i>
        @endif
        @if($icon && !$loading)
            <i class="bi bi-{{ $icon }} mr-2"></i>
        @endif
        {{ $slot }}
    </button>
@else
    <button type="button" {{ $attributes->merge(['class' => $classes]) }} {{ $disabled ? 'disabled' : '' }}>
        @if($loading)
            <i class="bi bi-arrow-repeat animate-spin mr-2"></i>
        @endif
        @if($icon && !$loading)
            <i class="bi bi-{{ $icon }} mr-2"></i>
        @endif
        {{ $slot }}
    </button>
@endif
