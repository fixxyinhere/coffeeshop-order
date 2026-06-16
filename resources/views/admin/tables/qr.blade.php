<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Code - Meja {{ $table->table_number }}</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>☕</text></svg>">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f5f5f5;
        }
        .card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 90%;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #3d1d08;
            margin-bottom: 8px;
        }
        .subtitle {
            font-size: 14px;
            color: #7a3d10;
            margin-bottom: 24px;
        }
        .qr-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            border: 2px solid #f2c99b;
            display: inline-block;
            margin-bottom: 24px;
        }
        .qr-container svg, .qr-container img {
            width: 300px;
            height: 300px;
        }
        .qr-container img {
            image-rendering: pixelated;
        }
        .table-number {
            font-size: 36px;
            font-weight: 800;
            color: #3d1d08;
            margin-bottom: 16px;
        }
        .url {
            font-size: 12px;
            color: #999;
            word-break: break-all;
            margin-bottom: 24px;
        }
        button {
            background: #b8651a;
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        button:hover { background: #974f14; }
        @media print {
            body { background: white; padding: 0; }
            .card { box-shadow: none; padding: 20px; }
            button { display: none; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="title">☕ Coffeeshop Order</div>
        <div class="subtitle">Scan untuk memesan</div>
        <div class="table-number">Meja {{ $table->table_number }}</div>
        <div class="qr-container">
            {!! QrCode::size(300)->generate($table->menu_url) !!}
        </div>
        <div class="url">{{ $table->menu_url }}</div>
        <button onclick="window.print()">🖨 Cetak QR Code</button>
    </div>
</body>
</html>
