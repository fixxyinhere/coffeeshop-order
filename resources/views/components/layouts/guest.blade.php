<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Coffeeshop') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#f8f5f1]">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
        <!-- Brand -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3">
                <span class="text-5xl">☕</span>
                <div class="text-left">
                    <h1 class="font-heading text-3xl text-coffee-800">Coffeeshop</h1>
                    <p class="text-sm text-coffee-500 font-medium">Order Management</p>
                </div>
            </a>
        </div>

        <!-- Card -->
        <div class="w-full sm:max-w-md bg-white rounded-2xl shadow-elevated border border-coffee-100 overflow-hidden">
            <div class="px-8 py-8">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <p class="mt-6 text-xs text-coffee-400">&copy; {{ date('Y') }} Coffeeshop Order</p>
    </div>
</body>
</html>
