@props(['status', 'label' => null])

@php
    $config = [
        'pending' => ['class' => 'badge-warning', 'icon' => '🕐'],
        'processing' => ['class' => 'badge-info', 'icon' => '👨‍🍳'],
        'ready' => ['class' => 'badge-success', 'icon' => '✅'],
        'completed' => ['class' => 'badge-success', 'icon' => '💳'],
        'cancelled' => ['class' => 'badge-danger', 'icon' => '❌'],
    ];
    $cfg = $config[$status] ?? ['class' => 'bg-gray-100 text-gray-600', 'icon' => ''];
    $displayLabel = $label ?? ucfirst($status);
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . $cfg['class']]) }}>
    {{ $cfg['icon'] }}&nbsp;{{ $displayLabel }}
</span>
