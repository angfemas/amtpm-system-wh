@props([
    'status' => 'default',
    'size' => 'md',
    'icon' => null,
    'pulse' => false
])

@php
$statusClasses = [
    'submitted' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'approved' => 'bg-blue-100 text-blue-800 border-blue-200',
    'completed' => 'bg-green-100 text-green-800 border-green-200',
    'overdue' => 'bg-red-100 text-red-800 border-red-200',
    'red-tag' => 'bg-red-900 text-white border-red-700',
    'white-tag' => 'bg-gray-100 text-gray-800 border-gray-300',
    'pending' => 'bg-orange-100 text-orange-800 border-orange-200',
    'cancelled' => 'bg-gray-100 text-gray-600 border-gray-300',
    'active' => 'bg-green-100 text-green-800 border-green-200',
    'inactive' => 'bg-gray-100 text-gray-600 border-gray-300',
    'draft' => 'bg-gray-100 text-gray-700 border-gray-300',
    'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
    'success' => 'bg-green-100 text-green-800 border-green-200',
    'error' => 'bg-red-100 text-red-800 border-red-200',
    'warning' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'info' => 'bg-blue-100 text-blue-800 border-blue-200',
    'default' => 'bg-gray-100 text-gray-800 border-gray-300',
];

$sizeClasses = [
    'xs' => 'px-1.5 py-0.5 text-xs',
    'sm' => 'px-2 py-1 text-xs',
    'md' => 'px-2.5 py-1 text-xs',
    'lg' => 'px-3 py-1.5 text-sm',
];

$iconClasses = [
    'submitted' => 'bi-clock-fill',
    'approved' => 'bi-check-circle-fill',
    'completed' => 'bi-check-lg',
    'overdue' => 'bi-exclamation-triangle-fill',
    'red-tag' => 'bi-exclamation-triangle-fill',
    'white-tag' => 'bi-tag-fill',
    'pending' => 'bi-hourglass-split',
    'cancelled' => 'bi-x-circle-fill',
    'active' => 'bi-check-circle-fill',
    'inactive' => 'bi-x-circle',
    'draft' => 'bi-file-earmark-text',
    'processing' => 'bi-arrow-repeat',
    'success' => 'bi-check-lg',
    'error' => 'bi-x-lg',
    'warning' => 'bi-exclamation-triangle-fill',
    'info' => 'bi-info-circle-fill',
];

$baseClasses = 'inline-flex items-center font-medium rounded-full border transition-all duration-200';
$pulseClasses = $pulse ? 'animate-pulse' : '';
$classes = implode(' ', [
    $baseClasses,
    $statusClasses[$status] ?? $statusClasses['default'],
    $sizeClasses[$size] ?? $sizeClasses['md'],
    $pulseClasses
]);

$displayIcon = $icon ?? ($iconClasses[$status] ?? null);
$displayText = ucfirst(str_replace('-', ' ', $status));
@endphp

<span class="{{ $classes }}">
    @if($displayIcon)
        <i class="bi bi-{{ $displayIcon }} mr-1.5"></i>
    @endif
    {{ $displayText }}
</span>
