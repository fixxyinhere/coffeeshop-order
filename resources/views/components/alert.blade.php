@props(['type' => 'success', 'message' => null])

@php
    $config = [
        'success' => ['bg' => 'bg-green-100', 'border' => 'border-green-200', 'text' => 'text-green-800', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        'error' => ['bg' => 'bg-red-100', 'border' => 'border-red-200', 'text' => 'text-red-800', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
        'warning' => ['bg' => 'bg-yellow-100', 'border' => 'border-yellow-200', 'text' => 'text-yellow-800', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z'],
        'info' => ['bg' => 'bg-blue-100', 'border' => 'border-blue-200', 'text' => 'text-blue-800', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
    $cfg = $config[$type] ?? $config['success'];
    $msg = $message ?? session($type);
@endphp

@if ($msg)
    <div {{ $attributes->merge(['class' => $cfg['bg'] . ' ' . $cfg['border'] . ' ' . $cfg['text'] . ' rounded-lg px-4 py-3 text-sm flex items-center gap-2']) }}>
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cfg['icon'] }}" />
        </svg>
        {{ $msg }}
    </div>
@endif
