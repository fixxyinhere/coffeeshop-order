<x-layouts.admin>
    <div x-data="reportChart()">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-coffee-800">Laporan Keuangan</h2>
            <div class="flex gap-2">
                <form action="{{ route('admin.reports.export') }}" method="GET" class="flex gap-2 items-center">
                    <input type="hidden" name="period" value="{{ $period }}">
                    @if (request('date_from')) <input type="hidden" name="date_from" value="{{ request('date_from') }}"> @endif
                    @if (request('date_to')) <input type="hidden" name="date_to" value="{{ request('date_to') }}"> @endif
                    @if (request('payment_method')) <input type="hidden" name="payment_method" value="{{ request('payment_method') }}"> @endif
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                        Export PDF
                    </button>
                </form>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" class="bg-white rounded-xl border border-coffee-100 p-4 mb-6 shadow-sm">
            <div class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs font-medium text-coffee-600 mb-1">Periode</label>
                    <select name="period" class="border-coffee-200 rounded-lg text-sm focus:border-coffee-500 focus:ring-coffee-500" onchange="this.form.submit()">
                        <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>
                <div x-show="$el.closest('form').querySelector('[name=period]').value === 'custom'">
                    <label class="block text-xs font-medium text-coffee-600 mb-1">Dari</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="border-coffee-200 rounded-lg text-sm focus:border-coffee-500 focus:ring-coffee-500">
                </div>
                <div x-show="$el.closest('form').querySelector('[name=period]').value === 'custom'">
                    <label class="block text-xs font-medium text-coffee-600 mb-1">Sampai</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="border-coffee-200 rounded-lg text-sm focus:border-coffee-500 focus:ring-coffee-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-coffee-600 mb-1">Pembayaran</label>
                    <select name="payment_method" class="border-coffee-200 rounded-lg text-sm focus:border-coffee-500 focus:ring-coffee-500">
                        <option value="">Semua</option>
                        <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Tunai</option>
                        <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>
                <button type="submit" class="bg-coffee-700 hover:bg-coffee-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">Filter</button>
            </div>
        </form>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-coffee-100 p-4 shadow-sm">
                <p class="text-xs text-coffee-500">Total Pendapatan</p>
                <p class="text-xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-coffee-100 p-4 shadow-sm">
                <p class="text-xs text-coffee-500">Total Transaksi</p>
                <p class="text-xl font-bold text-coffee-800">{{ $totalTransactions }}</p>
            </div>
            <div class="bg-white rounded-xl border border-coffee-100 p-4 shadow-sm">
                <p class="text-xs text-coffee-500">Rata-rata Order</p>
                <p class="text-xl font-bold text-blue-600">Rp {{ number_format($averageOrder, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-coffee-100 p-4 shadow-sm">
                <p class="text-xs text-coffee-500">Item Terjual</p>
                <p class="text-xl font-bold text-coffee-800">{{ $totalItemsSold }}</p>
            </div>
        </div>

        <!-- Revenue Trend Chart -->
        <div class="bg-white rounded-xl border border-coffee-100 p-4 shadow-sm mb-6">
            <h3 class="font-semibold text-coffee-700 mb-4">Tren Pendapatan</h3>
            <canvas id="revenueTrendChart" height="200"></canvas>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-xl border border-coffee-100 p-4 shadow-sm mb-6">
            <h3 class="font-semibold text-coffee-700 mb-4">Produk Terlaris</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-coffee-50 text-coffee-700 text-xs uppercase tracking-wider">
                            <th class="text-left px-4 py-3">Item</th>
                            <th class="text-center px-4 py-3">Qty Terjual</th>
                            <th class="text-right px-4 py-3">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-coffee-100">
                        @foreach ($topProducts as $product)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $product->menu_item_name }}</td>
                                <td class="px-4 py-3 text-center">{{ $product->total_qty }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-xl border border-coffee-100 overflow-hidden shadow-sm">
            <div class="p-4 border-b border-coffee-100">
                <h3 class="font-semibold text-coffee-700">Detail Transaksi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-coffee-50 text-coffee-700 text-xs uppercase tracking-wider">
                            <th class="text-left px-4 py-3">No. Order</th>
                            <th class="text-left px-4 py-3">Meja</th>
                            <th class="text-left px-4 py-3">Waktu</th>
                            <th class="text-left px-4 py-3">Kasir</th>
                            <th class="text-left px-4 py-3">Metode</th>
                            <th class="text-right px-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-coffee-100">
                        @foreach ($orders as $order)
                            <tr class="hover:bg-coffee-50">
                                <td class="px-4 py-3 font-medium">{{ $order->order_number }}</td>
                                <td class="px-4 py-3">{{ $order->table->table_number }}</td>
                                <td class="px-4 py-3 text-coffee-500">{{ $order->created_at->format('d/m H:i') }}</td>
                                <td class="px-4 py-3">{{ $order->confirmedBy->name ?? '-' }}</td>
                                <td class="px-4 py-3 capitalize">{{ $order->payment_method ?? '-' }}</td>
                                <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function reportChart() {
            return {
                init() {
                    const ctx = document.getElementById('revenueTrendChart');
                    if (ctx) {
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: @json(array_column($revenueTrend, 'date')),
                                datasets: [{
                                    label: 'Pendapatan',
                                    data: @json(array_column($revenueTrend, 'revenue')),
                                    borderColor: '#b8651a',
                                    backgroundColor: 'rgba(184,101,26,0.1)',
                                    fill: true,
                                    tension: 0.4,
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: { callback: (v) => 'Rp' + new Intl.NumberFormat('id-ID').format(v) }
                                    }
                                }
                            }
                        });
                    }
                }
            };
        }
    </script>
    @endpush
</x-layouts.admin>
