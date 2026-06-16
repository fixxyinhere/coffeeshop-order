<x-layouts.customer :table="$table">
    <div class="px-4 py-8 text-center">
        <!-- Order Number -->
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-coffee-100 text-coffee-600 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <p class="text-sm font-medium text-coffee-500 mb-1 uppercase tracking-wider">Nomor Antrian</p>
            <h1 class="font-heading text-4xl text-coffee-800">{{ $order->order_number }}</h1>
        </div>

        <!-- Progress Indicator -->
        <div class="max-w-sm mx-auto mb-8" x-data="orderStatus('{{ $order->status }}', {{ $order->id }})">
            <div class="card p-6 space-y-6">
                @php
                    $steps = [
                        'pending' => ['label' => 'Pesanan Diterima', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'processing' => ['label' => 'Sedang Diproses', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                        'ready' => ['label' => 'Siap Diambil', 'icon' => 'M5 13l4 4L19 7'],
                        'completed' => ['label' => 'Selesai', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                @endphp
                @foreach ($steps as $key => $step)
                    <div class="flex items-center gap-4" x-show="showStep('{{ $key }}')"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                             :class="{ 'bg-green-500 text-white': stepReached('{{ $key }}'), 'bg-coffee-200 text-coffee-400': !stepReached('{{ $key }}') && currentStep() === '{{ $key }}', 'bg-gray-200 text-gray-400': !stepReached('{{ $key }}') && currentStep() !== '{{ $key }}' }">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="font-semibold text-sm"
                               :class="{ 'text-green-600': stepReached('{{ $key }}'), 'text-coffee-700': !stepReached('{{ $key }}') && currentStep() === '{{ $key }}', 'text-gray-400': !stepReached('{{ $key }}') && currentStep() !== '{{ $key }}' }">
                                {{ $step['label'] }}
                            </p>
                            <p class="text-xs text-coffee-400" x-show="stepReached('{{ $key }}')">✓ Selesai</p>
                            <p class="text-xs text-coffee-500" x-show="!stepReached('{{ $key }}') && currentStep() === '{{ $key }}'" x-text="statusText"></p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div x-show="currentStatus === 'ready'"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="scale-75 opacity-0"
                 x-transition:enter-end="scale-100 opacity-100"
                 class="mt-8 p-8 bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-300 rounded-2xl shadow-lg">
                <div class="w-16 h-16 mx-auto bg-green-500 rounded-full flex items-center justify-center mb-4 animate-bounce">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="font-heading text-2xl text-green-700 mb-2">Pesanan Kamu Siap!</h3>
                <p class="text-sm text-green-600">Silakan ke kasir untuk mengambil pesanan.</p>
            </div>

            <div x-show="currentStatus === 'completed'"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="scale-75 opacity-0"
                 x-transition:enter-end="scale-100 opacity-100"
                 class="mt-8 p-8 bg-gradient-to-br from-gray-50 to-coffee-50 border-2 border-coffee-200 rounded-2xl shadow-lg">
                <div class="w-16 h-16 mx-auto bg-coffee-500 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="font-heading text-2xl text-coffee-700 mb-2">Pesanan Selesai</h3>
                <p class="text-sm text-coffee-600">Terima kasih! Selamat menikmati ☕</p>
            </div>

            <div x-show="currentStatus === 'cancelled'" class="mt-8 p-8 bg-gradient-to-br from-red-50 to-rose-50 border-2 border-red-300 rounded-2xl shadow-lg">
                <div class="w-16 h-16 mx-auto bg-red-500 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h3 class="font-heading text-2xl text-red-700 mb-2">Pesanan Dibatalkan</h3>
                <p class="text-sm text-red-600">Silakan hubungi kasir untuk info lebih lanjut.</p>
            </div>
        </div>

        <div class="text-left bg-coffee-50 rounded-xl p-4 border border-coffee-100 mt-6">
            <h3 class="font-semibold text-sm text-coffee-700 mb-3">Ringkasan Pesanan</h3>
            <div class="space-y-2">
                @foreach ($order->items as $item)
                    <div class="flex justify-between items-start text-sm">
                        <div>
                            <span class="font-medium text-coffee-800">{{ $item->quantity }}x {{ $item->menu_item_name }}</span>
                            @if ($item->options->count() > 0)
                                <p class="text-xs text-coffee-500">{{ $item->options->pluck('option_value')->join(', ') }}</p>
                            @endif
                            @if ($item->notes) <p class="text-xs text-coffee-400 italic">{{ $item->notes }}</p> @endif
                        </div>
                        <span class="text-coffee-700">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="border-t border-coffee-200 mt-3 pt-3 flex justify-between font-bold text-coffee-800">
                <span>Total</span>
                <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <script>
        function orderStatus(initialStatus, orderId) {
            return {
                currentStatus: initialStatus, pollingInterval: null,
                init() { if (['pending','processing','ready'].includes(this.currentStatus)) this.startPolling(); },
                startPolling() { this.pollingInterval = setInterval(() => this.checkStatus(), 5000); },
                async checkStatus() {
                    try {
                        const response = await fetch('/api/order/' + orderId + '/status');
                        const result = await response.json();
                        if (result.success) {
                            this.currentStatus = result.data.status;
                            if (['completed','cancelled'].includes(this.currentStatus)) clearInterval(this.pollingInterval);
                        }
                    } catch (e) { console.error('Polling error:', e); }
                },
                currentStep() { return ['pending','processing','ready','completed'].indexOf(this.currentStatus); },
                stepReached(step) { return ['pending','processing','ready','completed'].indexOf(step) < ['pending','processing','ready','completed'].indexOf(this.currentStatus); },
                showStep(step) { return ['pending','processing','ready','completed'].indexOf(step) <= ['pending','processing','ready','completed'].indexOf(this.currentStatus) || step === 'pending'; },
                get statusText() { return {pending:'Menunggu konfirmasi kasir...',processing:'Sedang dibuat...',ready:'Siap diambil!',completed:'Selesai',cancelled:'Dibatalkan'}[this.currentStatus] || ''; }
            };
        }
    </script>
</x-layouts.customer>
