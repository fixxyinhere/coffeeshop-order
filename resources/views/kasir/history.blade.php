<x-layouts.kasir>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-bold text-lg text-coffee-800">Riwayat Transaksi</h2>
            <div class="text-right">
                <p class="text-sm text-coffee-500">Total Pemasukan Hari Ini</p>
                <p class="text-2xl font-black text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-coffee-100 p-4 mb-4">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs font-medium text-coffee-600 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ $date }}" class="border-coffee-200 rounded-lg text-sm focus:border-coffee-500 focus:ring-coffee-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-coffee-600 mb-1">Status</label>
                    <select name="status" class="border-coffee-200 rounded-lg text-sm focus:border-coffee-500 focus:ring-coffee-500">
                        <option value="">Semua</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
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
            </form>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="bg-white rounded-xl border border-coffee-100 p-4">
                <p class="text-xs text-coffee-500">Transaksi</p>
                <p class="text-xl font-bold text-coffee-800">{{ $totalTransactions }}</p>
            </div>
            <div class="bg-white rounded-xl border border-coffee-100 p-4">
                <p class="text-xs text-coffee-500">Pemasukan</p>
                <p class="text-xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-coffee-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-coffee-50 text-coffee-700 text-xs uppercase tracking-wider">
                            <th class="text-left px-4 py-3">No. Order</th>
                            <th class="text-left px-4 py-3">Meja</th>
                            <th class="text-left px-4 py-3">Waktu</th>
                            <th class="text-left px-4 py-3">Kasir</th>
                            <th class="text-left px-4 py-3">Metode</th>
                            <th class="text-left px-4 py-3">Status</th>
                            <th class="text-right px-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-coffee-100">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-coffee-50 transition">
                                <td class="px-4 py-3 font-medium">{{ $order->order_number }}</td>
                                <td class="px-4 py-3">{{ $order->table->table_number }}</td>
                                <td class="px-4 py-3 text-coffee-500">{{ $order->created_at->format('H:i') }}</td>
                                <td class="px-4 py-3">{{ $order->confirmedBy->name ?? '-' }}</td>
                                <td class="px-4 py-3 capitalize">{{ $order->payment_method ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-8 text-coffee-400">Tidak ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.kasir>
