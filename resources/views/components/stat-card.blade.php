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

<div {{ $attributes->merge(['class' => 'stat-card']) }}>
    <p class="text-xs font-medium text-coffee-500 mb-1.5 uppercase tracking-wider">{{ $label }}</p>
    <p class="text-2xl font-heading {{ $valueColor }}">{{ $value }}</p>
</div>
