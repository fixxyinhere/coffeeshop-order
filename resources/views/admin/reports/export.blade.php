<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan - Coffeeshop Order</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>☕</text></svg>">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 11px; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin-bottom: 4px; }
        .header p { font-size: 11px; color: #666; }
        .summary { display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap; }
        .summary-item { flex: 1; min-width: 120px; }
        .summary-item label { font-size: 9px; color: #666; text-transform: uppercase; }
        .summary-item .value { font-size: 14px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 6px 8px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f5f5f5; font-size: 9px; text-transform: uppercase; }
        td { font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mb-1 { margin-bottom: 4px; }
        .footer { text-align: center; margin-top: 30px; font-size: 11px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>☕ Coffeeshop Order</h1>
        <p>Laporan Keuangan</p>
        <p>{{ $dateRange }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <label>Total Pendapatan</label>
            <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <label>Total Transaksi</label>
            <div class="value">{{ $totalTransactions }}</div>
        </div>
        <div class="summary-item">
            <label>Rata-rata Order</label>
            <div class="value">Rp {{ $totalTransactions > 0 ? number_format($totalRevenue / $totalTransactions, 0, ',', '.') : '0' }}</div>
        </div>
        <div class="summary-item">
            <label>Item Terjual</label>
            <div class="value">{{ $totalItemsSold }}</div>
        </div>
    </div>

    <h3 style="margin-bottom: 8px;">Produk Terlaris</h3>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topProducts as $product)
                <tr>
                    <td>{{ $product->menu_item_name }}</td>
                    <td class="text-center">{{ $product->total_qty }}</td>
                    <td class="text-right">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="margin-bottom: 8px;">Detail Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th>No. Order</th>
                <th>Waktu</th>
                <th>Meja</th>
                <th>Kasir</th>
                <th>Metode</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('d/m H:i') }}</td>
                    <td>{{ $order->table->table_number }}</td>
                    <td>{{ $order->confirmedBy->name ?? '-' }}</td>
                    <td>{{ $order->payment_method ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada {{ now()->format('d/m/Y H:i') }}</p>
        <p>Terima kasih</p>
    </div>
</body>
</html>
