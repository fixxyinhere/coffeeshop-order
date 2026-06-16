<x-layouts.kasir>
    <div x-data="kasirDashboard({{ $pendingCount }})" x-init="init()" class="flex flex-col lg:flex-row gap-6">
        <div class="flex-1 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-lg text-coffee-800">
                    Antrian Pesanan
                    <span x-show="pendingCount > 0" class="text-sm bg-yellow-400 text-yellow-900 px-2 py-0.5 rounded-full ml-1" x-text="pendingCount"></span>
                </h2>
                <span class="text-xs text-coffee-400" x-text="'Update: ' + lastUpdate"></span>
            </div>

            <div id="orders-container" class="space-y-3">
                @forelse ($activeOrders as $order)
                    <div class="order-card bg-white rounded-xl border-2 shadow-sm overflow-hidden"
                         style="border-color: {{ $order->status === 'pending' ? '#eab308' : ($order->status === 'processing' ? '#3b82f6' : '#22c55e') }}">
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-black text-coffee-800">{{ $order->order_number }}</h3>
                                    <p class="text-sm text-coffee-500">Meja {{ $order->table->table_number }} • {{ $order->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full
                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->status === 'ready' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ $order->status_label }}
                                </span>
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

                            <div class="flex gap-2">
                                @if ($order->status === 'pending')
                                    <form action="{{ route('kasir.orders.accept', $order) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white text-sm font-semibold py-2 rounded-lg transition">✓ Terima</button>
                                    </form>
                                    <form action="{{ route('kasir.orders.cancel', $order) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 rounded-lg transition">✗ Tolak</button>
                                    </form>
                                @elseif ($order->status === 'processing')
                                    <form action="{{ route('kasir.orders.ready', $order) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold py-2 rounded-lg transition">Tandai Siap ✓</button>
                                    </form>
                                    <form action="{{ route('kasir.orders.cancel', $order) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-4 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold py-2 rounded-lg transition">✕</button>
                                    </form>
                                @elseif ($order->status === 'ready')
                                    <button @click="openPayment({{ $order->id }})"
                                            class="w-full bg-purple-500 hover:bg-purple-600 text-white text-sm font-semibold py-2 rounded-lg transition">
                                        Konfirmasi Pembayaran
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-coffee-400">
                        <svg class="w-16 h-16 mx-auto mb-3 text-coffee-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p>Tidak ada pesanan aktif</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="lg:w-80 space-y-4">
            <div class="bg-white rounded-xl border border-coffee-100 shadow-sm">
                <div class="p-4 border-b border-coffee-100">
                    <h3 class="font-bold text-sm text-coffee-800">Kelola Menu</h3>
                    <p class="text-xs text-coffee-400">Toggle Sold Out</p>
                </div>
                <div class="p-3 space-y-1 max-h-[60vh] overflow-y-auto">
                    @foreach ($menuItems->groupBy(fn($item) => $item->category->name) as $categoryName => $items)
                        <p class="text-xs font-semibold text-coffee-500 px-2 pt-2 pb-1">{{ $categoryName }}</p>
                        @foreach ($items as $item)
                            <div class="flex items-center justify-between px-2 py-2 hover:bg-coffee-50 rounded-lg transition">
                                <span class="text-sm text-coffee-800 truncate flex-1">{{ $item->name }}</span>
                                <button @click="toggleAvailability({{ $item->id }})"
                                        :class="'relative w-10 h-5 rounded-full transition ' + ({{ $item->is_available ? 'true' : 'false' }} ? 'bg-green-400' : 'bg-gray-300')">
                                    <span class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition"
                                          :class="{ 'translate-x-5': {{ $item->is_available ? 'true' : 'false' }} }"></span>
                                </button>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>

        <div x-show="showPaymentModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
             @click.self="showPaymentModal = false">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <h3 class="font-bold text-lg text-coffee-800 mb-4">Konfirmasi Pembayaran</h3>
                    <div id="payment-order-summary" class="space-y-2 text-sm mb-4"></div>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-coffee-700 mb-1">Metode Pembayaran</label>
                            <select x-model="paymentMethod" class="w-full border-coffee-200 rounded-lg text-sm focus:border-coffee-500 focus:ring-coffee-500">
                                <option value="cash">Tunai</option>
                                <option value="qris">QRIS</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        <div x-show="paymentMethod === 'cash'">
                            <label class="block text-sm font-medium text-coffee-700 mb-1">Uang Diterima</label>
                            <input type="number" x-model="cashReceived" placeholder="0"
                                   class="w-full border-coffee-200 rounded-lg text-sm focus:border-coffee-500 focus:ring-coffee-500">
                            <template x-if="cashReceived > 0">
                                <div class="mt-2 p-3 bg-green-50 rounded-lg">
                                    <p class="text-sm">
                                        <span class="text-coffee-600">Kembalian: </span>
                                        <span class="font-bold text-green-700" x-text="'Rp ' + formatNumber(change)"></span>
                                    </p>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button @click="showPaymentModal = false" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 rounded-lg text-sm font-semibold transition">Batal</button>
                        <button @click="confirmPayment()" class="flex-1 bg-coffee-700 hover:bg-coffee-800 text-white py-2.5 rounded-lg text-sm font-semibold transition">Konfirmasi & Cetak Struk</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function kasirDashboard(initialPending) {
            return {
                pendingCount: initialPending, showPaymentModal: false, paymentOrderId: null,
                paymentMethod: 'cash', cashReceived: 0, lastUpdate: 'baru saja', pendingOrders: [],
                get change() {
                    const order = this.pendingOrders.find(o => o.id === this.paymentOrderId);
                    return order ? this.cashReceived - order.total : 0;
                },
                formatNumber(num) { return new Intl.NumberFormat('id-ID').format(Math.round(num)); },
                init() { this.startPolling(); this.updateTitle(); },
                startPolling() { setInterval(() => { this.fetchOrders(); }, 5000); },
                async fetchOrders() {
                    try {
                        const response = await fetch('/api/kasir/new-orders');
                        const result = await response.json();
                        if (result.success) {
                            this.pendingCount = result.data.pending_count;
                            this.updateTitle();
                            this.lastUpdate = new Date().toLocaleTimeString('id-ID');
                            if (result.data.pending_count > 0 && result.data.orders.some(o => o.status === 'pending')) {
                                this.playNotificationSound();
                                setTimeout(() => location.reload(), 1000);
                            }
                        }
                    } catch (e) { console.error('Polling error:', e); }
                },
                playNotificationSound() {
                    try {
                        const ctx = new (window.AudioContext || window.webkitAudioContext)();
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.connect(gain); gain.connect(ctx.destination);
                        osc.frequency.value = 800; osc.type = 'sine';
                        gain.gain.setValueAtTime(0.3, ctx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.5);
                        osc.start(ctx.currentTime); osc.stop(ctx.currentTime + 0.5);
                    } catch (e) {}
                },
                updateTitle() {
                    document.title = (this.pendingCount > 0 ? `(${this.pendingCount}) ` : '') + 'Dashboard Kasir';
                },
                async openPayment(orderId) {
                    this.paymentOrderId = orderId; this.paymentMethod = 'cash'; this.cashReceived = 0;
                    try {
                        const response = await fetch('/api/order/' + orderId + '/status');
                        const result = await response.json();
                        if (result.success) {
                            const order = result.data;
                            this.pendingOrders = this.pendingOrders.filter(o => o.id !== orderId);
                            this.pendingOrders.push({ id: order.id, total: order.total, order_number: order.order_number });
                            const summary = document.getElementById('payment-order-summary');
                            let html = `<div class="flex justify-between font-bold text-coffee-800 mb-2"><span>${order.order_number}</span><span>Rp ${new Intl.NumberFormat('id-ID').format(order.total)}</span></div>`;
                            order.items.forEach(item => {
                                html += `<div class="flex justify-between text-sm"><span>${item.quantity}x ${item.menu_item_name}</span><span>Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</span></div>`;
                            });
                            summary.innerHTML = html;
                        }
                    } catch (e) {}
                    this.showPaymentModal = true;
                },
                async confirmPayment() {
                    if (!this.paymentOrderId) return;
                    try {
                        const response = await fetch('/kasir/orders/' + this.paymentOrderId + '/payment', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify({ payment_method: this.paymentMethod, cash_received: this.cashReceived }),
                        });
                        const result = await response.json();
                        if (result.success) { this.showPaymentModal = false; window.open(result.redirect, '_blank'); location.reload(); }
                        else { alert(result.message || 'Terjadi kesalahan.'); }
                    } catch (e) { alert('Terjadi kesalahan: ' + e.message); }
                },
                async toggleAvailability(itemId) {
                    try {
                        await fetch('/kasir/menu/' + itemId + '/toggle', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } });
                        setTimeout(() => location.reload(), 500);
                    } catch (e) {}
                },
            };
        }
    </script>
</x-layouts.kasir>
