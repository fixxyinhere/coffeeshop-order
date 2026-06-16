@props(['status', 'label' => null])

@php
    $colors = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'processing' => 'bg-blue-100 text-blue-800',
        'ready' => 'bg-green-100 text-green-800',
        'completed' => 'bg-gray-100 text-gray-700',
        'cancelled' => 'bg-red-100 text-red-700',
    ];
    $class = $colors[$status] ?? 'bg-gray-100 text-gray-600';
    $displayLabel = $label ?? ucfirst($status);
@endphp

<span {{ $attributes->merge(['class' => 'text-xs font-semibold px-2 py-1 rounded-full ' . $class]) }}>
    {{ $displayLabel }}
</span>
