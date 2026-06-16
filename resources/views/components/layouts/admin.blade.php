<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') - Coffeeshop Order</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>☕</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-coffee-800 text-white flex-shrink-0 hidden md:flex flex-col">
            <div class="p-5 border-b border-coffee-700/50">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">☕</span>
                    <div>
                        <h1 class="font-heading text-lg leading-tight text-white">Coffeeshop</h1>
                        <p class="text-[11px] text-coffee-300 font-medium tracking-wide">Panel Admin</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 overflow-y-auto p-3 space-y-0.5">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('admin.dashboard') ? 'bg-coffee-700 text-white' : 'text-coffee-200 hover:bg-coffee-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.menu.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('admin.menu.*') ? 'bg-coffee-700 text-white' : 'text-coffee-200 hover:bg-coffee-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Menu
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('admin.categories.*') ? 'bg-coffee-700 text-white' : 'text-coffee-200 hover:bg-coffee-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Kategori
                </a>
                <a href="{{ route('admin.tables.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('admin.tables.*') ? 'bg-coffee-700 text-white' : 'text-coffee-200 hover:bg-coffee-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Meja
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('admin.users.*') ? 'bg-coffee-700 text-white' : 'text-coffee-200 hover:bg-coffee-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    Pengguna
                </a>
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('admin.reports.*') ? 'bg-coffee-700 text-white' : 'text-coffee-200 hover:bg-coffee-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Laporan
                </a>
            </nav>
            <div class="p-3 border-t border-coffee-700/50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-coffee-200 hover:bg-coffee-700 hover:text-white w-full transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile header -->
        <div class="md:hidden fixed top-0 left-0 right-0 z-30 bg-coffee-800 text-white px-4 py-3 flex items-center justify-between">
            <button onclick="document.getElementById('mobileSidebar').classList.toggle('hidden')" class="p-1.5 hover:bg-coffee-700 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <span class="font-heading text-base">☕ Coffeeshop</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs">Logout</button>
            </form>
        </div>

        <!-- Mobile Sidebar -->
        <div id="mobileSidebar" class="hidden fixed inset-0 z-40 md:hidden">
            <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('mobileSidebar').classList.add('hidden')"></div>
            <div class="absolute left-0 top-0 bottom-0 w-64 bg-coffee-800 p-4">
                <div class="flex items-center justify-between mb-4 border-b border-coffee-700 pb-4">
                    <span class="font-heading text-white text-lg">Menu</span>
                    <button onclick="document.getElementById('mobileSidebar').classList.add('hidden')" class="text-coffee-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded text-sm text-coffee-200 hover:bg-coffee-700">Dashboard</a>
                    <a href="{{ route('admin.menu.index') }}" class="block px-3 py-2 rounded text-sm text-coffee-200 hover:bg-coffee-700">Menu</a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded text-sm text-coffee-200 hover:bg-coffee-700">Kategori</a>
                    <a href="{{ route('admin.tables.index') }}" class="block px-3 py-2 rounded text-sm text-coffee-200 hover:bg-coffee-700">Meja</a>
                    <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded text-sm text-coffee-200 hover:bg-coffee-700">Pengguna</a>
                    <a href="{{ route('admin.reports.index') }}" class="block px-3 py-2 rounded text-sm text-coffee-200 hover:bg-coffee-700">Laporan</a>
                </nav>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden md:pt-0 pt-14">
            @if (session('success'))
                <div class="bg-emerald-50 border-b border-emerald-200 text-emerald-800 px-6 py-3 text-sm flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border-b border-red-200 text-red-800 px-6 py-3 text-sm flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <main class="flex-1 overflow-y-auto p-6 bg-[#f8f5f1]">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
