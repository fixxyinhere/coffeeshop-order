<x-layouts.admin>
    <div x-data="menuForm({{ json_encode($menu->options->groupBy('option_group')->map(function($opts, $group) {
        return ['name' => $group, 'values' => $opts->map(fn($o) => ['id' => $o->id, 'value' => $o->option_value, 'price' => $o->price_modifier])->toArray()];
    })->values()) }})">
        <div x-show="false">
            <template x-for="id in deletedOptionIds" :key="id">
                <input type="hidden" name="deleted_options[]" :value="id">
            </template>
        </div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-heading text-2xl text-coffee-800">Edit Menu: {{ $menu->name }}</h2>
        </div>

        <form action="{{ route('admin.menu.update', $menu) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-coffee-100 p-6 shadow-sm max-w-2xl">
            @csrf @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-coffee-700 mb-1">Nama Menu</label>
                    <input type="text" name="name" value="{{ old('name', $menu->name) }}" required
                           class="w-full border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-coffee-700 mb-1">Kategori</label>
                    <select name="category_id" required class="w-full border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $menu->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-coffee-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500">{{ old('description', $menu->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-coffee-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', $menu->price) }}" required min="0"
                               class="w-full border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-coffee-700 mb-1">Urutan</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $menu->sort_order) }}" min="0"
                               class="w-full border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-coffee-700 mb-1">Foto Menu</label>
                    @if ($menu->image)
                        <div class="mb-2">
                            <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" class="w-24 h-18 rounded object-cover">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="w-full text-sm border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-coffee-50 file:text-coffee-700 hover:file:bg-coffee-100">
                    <p class="text-xs text-coffee-500 mt-1.5">
                        Format: <strong>JPEG, PNG, JPG, WebP</strong> — Maks: <strong>5MB</strong>
                    </p>
                    @error('image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_available" value="1" {{ $menu->is_available ? 'checked' : '' }}
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
                                <input type="text" x-model="group.name" placeholder="Nama group"
                                       class="text-sm border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500 w-48" required>
                                <button type="button" @click="removeOptionGroup(gIdx)" class="text-red-500 hover:text-red-700 text-xs">Hapus</button>
                            </div>
                            <template x-for="(val, vIdx) in group.values" :key="vIdx">
                                <div class="flex items-center gap-2 mb-1">
                                    <input type="hidden" :name="'options[' + flatIdx(gIdx, vIdx) + '][id]'" :value="val.id || ''">
                                    <input type="hidden" :name="'options[' + flatIdx(gIdx, vIdx) + '][option_group]'" :value="group.name">
                                    <input type="text" x-model="val.value" :name="'options[' + flatIdx(gIdx, vIdx) + '][option_value]'" placeholder="Nilai"
                                           class="text-sm border-coffee-200 rounded-lg flex-1" required>
                                    <input type="number" x-model="val.price" :name="'options[' + flatIdx(gIdx, vIdx) + '][price_modifier]'" placeholder="+Rp"
                                           class="text-sm border-coffee-200 rounded-lg w-24" min="0">
                                    <button type="button" @click="removeOptionValue(gIdx, vIdx)" class="text-red-400 hover:text-red-600 text-xs">✕</button>
                                </div>
                            </template>
                            <button type="button" @click="group.values.push({id: '', value: '', price: 0})" class="text-xs text-coffee-500 hover:text-coffee-700 mt-1">
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
                    Update
                </button>
            </div>
        </form>
    </div>

    <script>
        function menuForm(existingGroups = []) {
            return {
                optionGroups: existingGroups.length ? existingGroups : [],
                deletedOptionIds: [],
                get deletedOptionIdsJson() {
                    return JSON.stringify(this.deletedOptionIds);
                },

                flatIdx(gIdx, vIdx) {
                    let idx = 0;
                    for (let i = 0; i < gIdx; i++) {
                        idx += this.optionGroups[i].values.length;
                    }
                    return idx + vIdx;
                },

                addOptionGroup() {
                    this.optionGroups.push({ name: '', values: [{ id: '', value: '', price: 0 }] });
                },

                removeOptionGroup(index) {
                    const group = this.optionGroups[index];
                    group.values.forEach(v => {
                        if (v.id) this.deletedOptionIds.push(v.id);
                    });
                    this.optionGroups.splice(index, 1);
                },

                removeOptionValue(gIdx, vIdx) {
                    const val = this.optionGroups[gIdx].values[vIdx];
                    if (val.id) this.deletedOptionIds.push(val.id);
                    this.optionGroups[gIdx].values.splice(vIdx, 1);
                }
            };
        }
    </script>
</x-layouts.admin>
