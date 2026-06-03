@props([
    'status' => 'default',
    'text' => null
])

@php
    $badgeClasses = [
        'submitted' => 'badge-submitted',
        'approved' => 'badge-approved', 
        'completed' => 'badge-completed',
        'overdue' => 'badge-overdue',
        'red-tag' => 'badge-red-tag',
        'white-tag' => 'badge-white-tag',
        'in-progress' => 'badge-approved',
        'scheduled' => 'badge-submitted',
        'cancelled' => 'bg-gray-100 text-gray-800',
        'pending' => 'badge-submitted'
    ];
    
    $class = $badgeClasses[$status] ?? 'bg-gray-100 text-gray-800';
@endphp

<span class="status-badge {{ $class }}">
    {{ $text ?? ucfirst(str_replace('-', ' ', $status)) }}
</span>
