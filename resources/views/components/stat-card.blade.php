@props(['label', 'value', 'color' => 'coffee'])

@php
    $colors = [
        'coffee' => 'text-coffee-800',
        'green' => 'text-green-600',
        'blue' => 'text-blue-600',
        'red' => 'text-red-600',
        'purple' => 'text-purple-600',
    ];
    $valueColor = $colors[$color] ?? $colors['coffee'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-coffee-100 p-4 shadow-sm']) }}>
    <p class="text-xs text-coffee-500 mb-1">{{ $label }}</p>
    <p class="text-xl font-bold {{ $valueColor }}">{{ $value }}</p>
</div>
