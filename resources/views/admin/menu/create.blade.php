<x-layouts.admin>
    <div x-data="menuForm()">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-coffee-800">Tambah Menu</h2>
        </div>

        <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-coffee-100 p-6 shadow-sm max-w-2xl">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-coffee-700 mb-1">Nama Menu</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-coffee-700 mb-1">Kategori</label>
                    <select name="category_id" required class="w-full border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-coffee-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-coffee-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="price" value="{{ old('price') }}" required min="0"
                               class="w-full border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-coffee-700 mb-1">Urutan</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                               class="w-full border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-coffee-700 mb-1">Foto Menu</label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full text-sm border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-coffee-50 file:text-coffee-700 hover:file:bg-coffee-100">
                </div>

                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_available" value="1" checked
                               class="rounded border-coffee-300 text-coffee-600 focus:ring-coffee-500">
                        <span class="text-sm text-coffee-700">Tersedia</span>
                    </label>
                </div>

                <!-- Options Section -->
                <div class="border-t border-coffee-100 pt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-sm text-coffee-700">Opsi Kustomisasi</h3>
                        <button type="button" @click="addOptionGroup()" class="text-xs bg-coffee-100 hover:bg-coffee-200 text-coffee-700 px-3 py-1 rounded-lg transition">
                            + Tambah Group
                        </button>
                    </div>

                    <template x-for="(group, gIdx) in optionGroups" :key="gIdx">
                        <div class="bg-coffee-50 rounded-lg p-3 mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <input type="text" x-model="group.name" :name="'options[' + gIdx + '][option_group]'" placeholder="Nama group (contoh: Ukuran)"
                                       class="text-sm border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500 w-48" required>
                                <button type="button" @click="optionGroups.splice(gIdx, 1)" class="text-red-500 hover:text-red-700 text-xs">Hapus</button>
                            </div>
                            <template x-for="(val, vIdx) in group.values" :key="vIdx">
                                <div class="flex items-center gap-2 mb-1">
                                    <input type="text" x-model="val.value" :name="'options[' + gIdx + '][option_value][]'" placeholder="Nilai (contoh: Besar)"
                                           class="text-sm border-coffee-200 rounded-lg flex-1" required>
                                    <input type="number" x-model="val.price" :name="'options[' + gIdx + '][price_modifier][]'" placeholder="+Rp"
                                           class="text-sm border-coffee-200 rounded-lg w-24" min="0">
                                    <button type="button" @click="group.values.splice(vIdx, 1)" class="text-red-400 hover:text-red-600 text-xs">✕</button>
                                </div>
                            </template>
                            <button type="button" @click="group.values.push({value: '', price: 0})" class="text-xs text-coffee-500 hover:text-coffee-700 mt-1">
                                + Tambah Nilai
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.menu.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="bg-coffee-700 hover:bg-coffee-800 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <script>
        function menuForm() {
            return {
                optionGroups: [],
                addOptionGroup() {
                    this.optionGroups.push({ name: '', values: [{ value: '', price: 0 }] });
                }
            };
        }
    </script>
</x-layouts.admin>
