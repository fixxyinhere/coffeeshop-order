<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meja Tidak Ditemukan - Coffeeshop Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #fdf6f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 20px;
            padding: 48px 32px;
            text-align: center;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            max-width: 400px;
            width: 100%;
        }
        .icon { font-size: 64px; margin-bottom: 16px; }
        h1 { font-size: 24px; font-weight: 800; color: #3d1d08; margin-bottom: 8px; }
        p { font-size: 14px; color: #7a3d10; line-height: 1.6; margin-bottom: 24px; }
        .btn {
            display: inline-block;
            background: #b8651a;
            color: white;
            text-decoration: none;
            padding: 12px 32px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.2s;
        }
        .btn:hover { background: #974f14; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">🔍</div>
        <h1>Meja Tidak Ditemukan</h1>
        <p>QR Code yang Anda scan tidak valid atau meja sudah tidak aktif. Silakan scan ulang QR Code atau hubungi staff coffeeshop.</p>
        <a href="/" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>
