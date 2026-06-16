<x-layouts.customer :table="$table">
    <!-- Category Tabs -->
    <div class="sticky top-14 z-20 bg-white border-b border-coffee-100 overflow-x-auto">
        <div class="flex gap-1 px-2 py-2 min-w-max">
            @foreach ($categories as $cat)
                <a href="#cat-{{ $cat->id }}"
                   class="px-4 py-2 rounded-full text-xs font-medium transition whitespace-nowrap
                          {{ $loop->first ? 'bg-coffee-700 text-white' : 'bg-coffee-100 text-coffee-700 hover:bg-coffee-200' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="px-4 py-4 space-y-6">
        @foreach ($categories as $category)
            <section id="cat-{{ $category->id }}">
                <h2 class="font-bold text-lg text-coffee-800 mb-3">{{ $category->name }}</h2>
                <div class="grid grid-cols-2 gap-3">
                    @foreach ($category->menuItems as $item)
                        <div class="bg-white rounded-xl border border-coffee-100 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div class="relative">
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                                     class="w-full aspect-[4/3] object-cover" loading="lazy"
                                     onerror="this.src='https://placehold.co/400x300/coffee/lavender?text={{ urlencode($item->name) }}'">
                                @if (!$item->is_available)
                                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold">Sold Out</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-3">
                                <h3 class="font-semibold text-sm text-coffee-800 leading-tight">{{ $item->name }}</h3>
                                <p class="text-coffee-600 font-bold text-sm mt-1">{{ $item->formatted_price }}</p>
                                @if ($item->is_available)
                                    <button @click="openCustomize({{ json_encode(['id' => $item->id, 'name' => $item->name, 'price' => (float)$item->price]) }}, {{ json_encode($item->options) }})"
                                            class="mt-2 w-full bg-coffee-700 hover:bg-coffee-800 text-white text-xs font-semibold py-2 rounded-lg transition active:scale-95">
                                        + Tambah
                                    </button>
                                @else
                                    <button disabled class="mt-2 w-full bg-gray-200 text-gray-400 text-xs font-semibold py-2 rounded-lg cursor-not-allowed">Tidak Tersedia</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>

    <!-- Customize Modal -->
    <div x-show="showingCustomize"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="fixed inset-0 z-50 flex flex-col bg-white">
        <div class="bg-coffee-800 text-white px-4 py-3 flex items-center justify-between">
            <h2 class="font-bold text-lg" x-text="customizeItem?.name"></h2>
            <button @click="showingCustomize = false" class="p-1 hover:bg-coffee-700 rounded">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            <template x-if="customizeItem && customizeItem.options">
                <div>
                    <template x-for="(groupOpts, groupName) in groupOptions(customizeItem.options)" :key="groupName">
                        <div class="mb-4">
                            <h3 class="font-semibold text-sm text-coffee-700 mb-2" x-text="groupName"></h3>
                            <div class="space-y-1" x-data>
                                <template x-for="opt in groupOpts" :key="opt.id">
                                    <label class="flex items-center gap-3 p-2 rounded-lg border border-coffee-200 hover:bg-coffee-50 cursor-pointer transition"
                                           @click="customizeOptions[groupName] = opt.id; calculateCustomizeSubtotal(customizeItem.options)">
                                        <input type="radio" :name="'opt_' + groupName" :value="opt.id"
                                               x-model="customizeOptions[groupName]"
                                               @change="calculateCustomizeSubtotal(customizeItem.options)"
                                               class="text-coffee-600 focus:ring-coffee-500">
                                        <span class="text-sm flex-1" x-text="opt.option_value"></span>
                                        <span x-show="parseFloat(opt.price_modifier) > 0" class="text-xs text-coffee-500" x-text="'+' + formatPrice(opt.price_modifier)"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <div>
                <h3 class="font-semibold text-sm text-coffee-700 mb-2">Jumlah</h3>
                <div class="flex items-center gap-4">
                    <button @click="if(customizeQty > 1) customizeQty--; calculateCustomizeSubtotal(customizeItem?.options || [])"
                            class="w-10 h-10 rounded-full bg-coffee-200 hover:bg-coffee-300 flex items-center justify-center font-bold text-coffee-800 text-lg">-</button>
                    <span class="font-bold text-lg w-8 text-center" x-text="customizeQty"></span>
                    <button @click="if(customizeQty < 99) customizeQty++; calculateCustomizeSubtotal(customizeItem?.options || [])"
                            class="w-10 h-10 rounded-full bg-coffee-600 hover:bg-coffee-700 text-white flex items-center justify-center font-bold text-lg">+</button>
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-sm text-coffee-700 mb-2">Catatan</h3>
                <textarea x-model="customizeNotes"
                          placeholder="Contoh: less sugar, pakai oat milk..."
                          class="w-full text-sm border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500 resize-none" rows="2"></textarea>
            </div>
        </div>

        <div class="border-t border-coffee-200 p-4 bg-coffee-50">
            <div class="flex justify-between items-center mb-3">
                <span class="text-coffee-600 text-sm">Subtotal</span>
                <span class="font-bold text-lg text-coffee-800" x-text="formatPrice(customizeSubtotal)"></span>
            </div>
            <button @click="addToCart()"
                    class="w-full bg-coffee-700 hover:bg-coffee-800 text-white py-3 rounded-xl font-bold text-sm transition active:scale-[0.98]">
                Tambah ke Keranjang
            </button>
        </div>
    </div>

    <script>
        function customerApp() {
            return {
                showCart: false, showingCustomize: false, cartItems: [], orderNotes: '', submitting: false,
                customizeItem: null, customizeQty: 1, customizeOptions: {}, customizeNotes: '', customizeSubtotal: 0,

                get subtotal() { return this.cartItems.reduce((sum, item) => sum + item.subtotal, 0); },
                formatPrice(price) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(price)); },

                groupOptions(options) {
                    if (!options || !options.length) return {};
                    const groups = {};
                    options.forEach(opt => {
                        if (!groups[opt.option_group]) groups[opt.option_group] = [];
                        groups[opt.option_group].push(opt);
                    });
                    return groups;
                },

                openCustomize(item, options) {
                    this.customizeItem = { ...item, options: options || [] };
                    this.customizeQty = 1; this.customizeNotes = ''; this.customizeOptions = {};
                    if (options && options.length > 0) {
                        const groups = [...new Set(options.map(o => o.option_group))];
                        groups.forEach(group => {
                            const firstOpt = options.find(o => o.option_group === group);
                            if (firstOpt) this.customizeOptions[group] = firstOpt.id;
                        });
                    }
                    this.calculateCustomizeSubtotal(options || []);
                    this.showingCustomize = true;
                },

                calculateCustomizeSubtotal(options) {
                    if (!this.customizeItem || !options) return;
                    let basePrice = parseFloat(this.customizeItem.price);
                    let modifiers = 0;
                    Object.values(this.customizeOptions).forEach(optId => {
                        const opt = options.find(o => o.id == optId);
                        if (opt) modifiers += parseFloat(opt.price_modifier || 0);
                    });
                    this.customizeSubtotal = (basePrice + modifiers) * this.customizeQty;
                },

                addToCart() {
                    const options = this.customizeItem?.options || [];
                    let optionText = '', optionData = [], modifiers = 0;
                    const groups = [...new Set(options.map(o => o.option_group))];
                    groups.forEach(group => {
                        const optId = this.customizeOptions[group];
                        const opt = options.find(o => o.id == optId);
                        if (opt) {
                            modifiers += parseFloat(opt.price_modifier || 0);
                            optionData.push({ option_group: opt.option_group, option_value: opt.option_value, price_modifier: parseFloat(opt.price_modifier || 0) });
                            optionText += (optionText ? ', ' : '') + opt.option_value;
                        }
                    });

                    const itemTotal = (parseFloat(this.customizeItem.price) + modifiers) * this.customizeQty;
                    this.cartItems.push({
                        menu_item_id: this.customizeItem.id, name: this.customizeItem.name,
                        price: parseFloat(this.customizeItem.price), qty: this.customizeQty,
                        notes: this.customizeNotes, optionsText: optionText, options: optionData, subtotal: itemTotal,
                    });
                    this.showingCustomize = false;
                    this.showCart = true;
                },

                increaseQty(index) {
                    if (this.cartItems[index].qty < 99) {
                        const item = this.cartItems[index]; item.qty++;
                        const mods = (item.options || []).reduce((s, o) => s + parseFloat(o.price_modifier || 0), 0);
                        item.subtotal = (item.price + mods) * item.qty;
                    }
                },

                decreaseQty(index) {
                    if (this.cartItems[index].qty > 1) {
                        const item = this.cartItems[index]; item.qty--;
                        const mods = (item.options || []).reduce((s, o) => s + parseFloat(o.price_modifier || 0), 0);
                        item.subtotal = (item.price + mods) * item.qty;
                    } else { this.removeItem(index); }
                },

                removeItem(index) { this.cartItems.splice(index, 1); if (!this.cartItems.length) this.showCart = false; },

                async submitOrder() {
                    if (this.submitting || !this.cartItems.length) return;
                    if (!confirm('Yakin ingin memesan? Pesanan tidak bisa diubah setelah dikirim.')) return;
                    this.submitting = true;
                    try {
                        const response = await fetch('{{ route('customer.order.store', ['token' => $table->qr_code_token]) }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify({
                                items: this.cartItems.map(i => ({ menu_item_id: i.menu_item_id, quantity: i.qty, notes: i.notes || '', options: i.options || [] })),
                                notes: this.orderNotes,
                            }),
                        });
                        const result = await response.json();
                        if (result.success) { this.cartItems = []; this.orderNotes = ''; this.showCart = false; window.location.href = result.data.redirect; }
                        else { alert(result.message || 'Terjadi kesalahan.'); }
                    } catch (error) { alert('Terjadi kesalahan. Silakan coba lagi.'); }
                    finally { this.submitting = false; }
                },
            };
        }
    </script>
</x-layouts.customer>
