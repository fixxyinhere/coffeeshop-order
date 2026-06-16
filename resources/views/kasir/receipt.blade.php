<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk - {{ $order->order_number }}</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>☕</text></svg>">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        .receipt {
            width: 300px;
            background: white;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .text-lg { font-size: 14px; }
        .text-sm { font-size: 10px; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
        .border-b { border-bottom: 1px dashed #ccc; padding-bottom: 8px; margin-bottom: 8px; }
        .border-t { border-top: 1px dashed #ccc; padding-top: 8px; margin-top: 8px; }
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .w-full { width: 100%; }
        button {
            display: block;
            width: 100%;
            padding: 12px;
            background: #b8651a;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover { background: #974f14; }
        @media print {
            body { background: white; padding: 0; }
            .receipt { box-shadow: none; border-radius: 0; }
            button { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="text-center mb-3">
            <div class="font-heading text-xl">☕ Coffeeshop Order</div>
            <div class="text-sm">Jl. Coffee No. 123</div>
            <div class="text-sm">Telp: 0812-3456-7890</div>
        </div>

        <div class="border-b"></div>

        <div class="flex justify-between text-sm mb-2">
            <span>No: {{ $order->order_number }}</span>
            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="text-sm mb-3">Meja: {{ $order->table->table_number }}</div>

        <div class="border-b"></div>

        <div class="flex justify-between text-sm font-bold mb-1">
            <span>Item</span>
            <span>Total</span>
        </div>

        @foreach ($order->items as $item)
            <div class="text-sm">
                <div class="flex justify-between">
                    <span>{{ $item->menu_item_name }}</span>
                    <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="text-xs text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->menu_item_price, 0, ',', '.') }}</div>
                @if ($item->options->count() > 0)
                    <div class="text-xs text-gray-500">{{ $item->options->pluck('option_value')->join(', ') }}</div>
                @endif
            </div>
        @endforeach

        <div class="border-t"></div>

        <div class="flex justify-between text-sm mb-1">
            <span>Subtotal</span>
            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-lg font-bold">
            <span>Total</span>
            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
        </div>

        <div class="border-t"></div>

        <div class="text-sm mb-1">
            <span>Metode Pembayaran: 
                @if ($order->payment_method === 'cash') Tunai
                @elseif ($order->payment_method === 'qris') QRIS
                @else Transfer
                @endif
            </span>
        </div>

        @if ($order->payment_method === 'cash' && $order->cash_received)
            <div class="text-sm mb-1">Uang Diterima: Rp {{ number_format($order->cash_received, 0, ',', '.') }}</div>
            <div class="text-sm mb-1">Kembalian: Rp {{ number_format($order->change_amount, 0, ',', '.') }}</div>
        @endif

        @if ($order->confirmedBy)
            <div class="text-sm mb-1">Kasir: {{ $order->confirmedBy->name }}</div>
        @endif

        <div class="border-b"></div>

        <div class="text-center mt-3">
            <p class="font-heading text-xl">Terima Kasih!</p>
            <p class="text-sm">Selamat menikmati pesanan Anda</p>
        </div>

        <button onclick="window.print()">🖨 Cetak Struk</button>
    </div>
</body>
</html>
