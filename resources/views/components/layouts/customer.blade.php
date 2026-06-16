<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Coffeeshop Order</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>☕</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-coffee-50 text-gray-900">
    <div x-data="customerApp()" class="min-h-screen max-w-lg mx-auto bg-white shadow-lg relative">
        <!-- Header -->
        <header class="sticky top-0 z-30 bg-coffee-800 text-white px-4 py-3 flex items-center justify-between shadow-md">
            <div class="flex items-center gap-2">
                <span class="text-2xl">☕</span>
                <div>
                    <h1 class="font-bold text-sm leading-tight">Coffeeshop Order</h1>
                    <p class="text-xs text-coffee-200">Meja {{ $table->table_number }}</p>
                </div>
            </div>
            <div class="text-xs text-coffee-200">Scan & Order</div>
        </header>

        <script>
            // Fallback customerApp — child views (e.g. menu.blade.php) override ini dengan versi lengkap
            function customerApp() {
                return {
                    showCart: false, showingCustomize: false, cartItems: [], orderNotes: '', submitting: false,
                    customizeItem: null, customizeQty: 1, customizeOptions: {}, customizeNotes: '', customizeSubtotal: 0,
                    get subtotal() { return 0; },
                    formatPrice(price) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(price)); },
                    openCustomize(item, options) {},
                    calculateCustomizeSubtotal(options) {},
                    addToCart(options) {},
                    increaseQty(index) {},
                    decreaseQty(index) {},
                    removeItem(index) {},
                    async submitOrder() {},
                };
            }
        </script>

        <!-- Main Content -->
        <main class="pb-24">
            {{ $slot }}
        </main>

        <!-- Cart FAB -->
        <template x-teleport="body">
            <button x-show="cartItems.length > 0"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="translate-y-20 opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="translate-y-0 opacity-100"
                    x-transition:leave-end="translate-y-20 opacity-0"
                    @click="showCart = true"
                    class="fixed bottom-6 right-6 z-50 bg-coffee-700 hover:bg-coffee-800 text-white rounded-full px-5 py-3 shadow-xl flex items-center gap-3 transition-all hover:scale-105 active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
                <span class="font-semibold" x-text="cartItems.length">0</span>
                <span class="hidden sm:inline text-sm">Pesanan</span>
            </button>
        </template>

        <!-- Cart Modal -->
        <div x-show="showCart"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="fixed inset-0 z-50 flex flex-col bg-white">
            <div class="bg-coffee-800 text-white px-4 py-3 flex items-center justify-between">
                <h2 class="font-bold text-lg">Pesanan Kamu</h2>
                <button @click="showCart = false" class="p-1 hover:bg-coffee-700 rounded">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                <template x-for="(item, index) in cartItems" :key="index">
                    <div class="bg-coffee-50 rounded-lg p-3 border border-coffee-100">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-semibold text-sm" x-text="item.name"></h4>
                                <p class="text-xs text-coffee-600 mt-0.5" x-text="'@' + formatPrice(item.price)"></p>
                                <template x-if="item.optionsText">
                                    <p class="text-xs text-coffee-500 mt-1" x-text="item.optionsText"></p>
                                </template>
                                <template x-if="item.notes">
                                    <p class="text-xs text-coffee-400 italic mt-0.5" x-text="'Catatan: ' + item.notes"></p>
                                </template>
                            </div>
                            <div class="flex items-center gap-2 ml-2">
                                <button @click="decreaseQty(index)" class="w-7 h-7 rounded-full bg-coffee-200 hover:bg-coffee-300 flex items-center justify-center font-bold text-coffee-800">-</button>
                                <span class="font-semibold text-sm w-5 text-center" x-text="item.qty"></span>
                                <button @click="increaseQty(index)" class="w-7 h-7 rounded-full bg-coffee-600 hover:bg-coffee-700 text-white flex items-center justify-center font-bold">+</button>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-2 pt-2 border-t border-coffee-100">
                            <button @click="removeItem(index)" class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                            <span class="text-sm font-semibold text-coffee-800" x-text="formatPrice(item.subtotal)"></span>
                        </div>
                    </div>
                </template>
                <template x-if="cartItems.length === 0">
                    <div class="text-center py-10 text-coffee-400">
                        <svg class="w-16 h-16 mx-auto mb-3 text-coffee-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                        </svg>
                        <p>Belum ada item dipilih</p>
                    </div>
                </template>
            </div>
            <div class="border-t border-coffee-200 p-4 space-y-3 bg-coffee-50">
                <div>
                    <label class="block text-xs font-medium text-coffee-700 mb-1">Catatan Pesanan</label>
                    <textarea x-model="orderNotes" placeholder="Catatan umum untuk pesanan (opsional)"
                              class="w-full text-sm border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500 resize-none" rows="2"></textarea>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-coffee-600">Subtotal</span>
                    <span class="font-bold text-coffee-800" x-text="formatPrice(subtotal)"></span>
                </div>
                <button @click="submitOrder" :disabled="submitting || cartItems.length === 0"
                        :class="submitting || cartItems.length === 0 ? 'bg-coffee-300 cursor-not-allowed' : 'bg-coffee-700 hover:bg-coffee-800 cursor-pointer'"
                        class="w-full py-3 rounded-xl text-white font-bold text-sm transition-all active:scale-[0.98]">
                    <span x-show="!submitting">Pesan Sekarang</span>
                    <span x-show="submitting">Mengirim...</span>
                </button>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
