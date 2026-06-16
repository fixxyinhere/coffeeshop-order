<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title id="pageTitle">Dashboard Kasir</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>☕</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Top Nav -->
        <nav class="bg-coffee-800 text-white px-4 py-3 flex items-center justify-between shadow-md">
            <div class="flex items-center gap-3">
                <span class="text-xl">☕</span>
                <span class="font-bold text-sm">Coffeeshop Order</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-coffee-200">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs bg-coffee-700 hover:bg-coffee-600 px-3 py-1.5 rounded-lg transition">
                        Logout
                    </button>
                </form>
            </div>
        </nav>

        <!-- Navigation Tabs -->
        <div class="bg-white border-b border-gray-200 px-4">
            <div class="flex gap-4 -mb-px">
                <a href="{{ route('kasir.dashboard') }}"
                   class="py-3 px-1 border-b-2 text-sm font-medium transition {{ request()->routeIs('kasir.dashboard') ? 'border-coffee-600 text-coffee-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Dashboard
                </a>
                <a href="{{ route('kasir.history') }}"
                   class="py-3 px-1 border-b-2 text-sm font-medium transition {{ request()->routeIs('kasir.history') ? 'border-coffee-600 text-coffee-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Riwayat
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="max-w-7xl mx-auto mt-4 px-4">
                <div class="bg-green-100 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto mt-4 px-4">
                <div class="bg-red-100 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 py-6">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>
