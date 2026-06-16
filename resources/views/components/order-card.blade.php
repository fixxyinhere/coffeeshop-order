@props(['order'])

<div class="order-card bg-white rounded-xl border-2 shadow-sm overflow-hidden"
     style="border-color: {{ $order->status === 'pending' ? '#eab308' : ($order->status === 'processing' ? '#3b82f6' : ($order->status === 'ready' ? '#22c55e' : '#6b7280')) }}">
    <div class="p-4">
        <div class="flex items-start justify-between mb-3">
            <div>
                <h3 class="text-xl font-black text-coffee-800">{{ $order->order_number }}</h3>
                <p class="text-sm text-coffee-500">Meja {{ $order->table->table_number }} • {{ $order->created_at->diffForHumans() }}</p>
            </div>
            <x-status-badge :status="$order->status" :label="$order->status_label" />
        </div>

        <div class="space-y-1 mb-3">
            @foreach ($order->items as $item)
                <div class="text-sm">
                    <div class="flex justify-between">
                        <span class="font-medium">{{ $item->quantity }}x {{ $item->menu_item_name }}</span>
                        <span class="text-coffee-600">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($item->options->count() > 0)
                        <p class="text-xs text-coffee-400 ml-4">{{ $item->options->pluck('option_value')->join(', ') }}</p>
                    @endif
                    @if ($item->notes)
                        <p class="text-xs text-coffee-400 italic ml-4">Catatan: {{ $item->notes }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        @if ($order->notes)
            <p class="text-xs bg-coffee-50 rounded p-2 text-coffee-600 italic mb-3">Catatan: {{ $order->notes }}</p>
        @endif

        <div class="flex justify-between items-center mb-3">
            <span class="text-sm text-coffee-500">Total</span>
            <span class="font-bold text-lg text-coffee-800">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
        </div>

        <div {{ $attributes->merge(['class' => 'flex gap-2']) }}>
            {{ $slot }}
        </div>
    </div>
</div>
