<x-layouts.admin>
    <div>
        <h2 class="text-2xl font-bold text-coffee-800 mb-6">Edit Meja: {{ $table->table_number }}</h2>

        <div class="grid md:grid-cols-2 gap-6">
            <form action="{{ route('admin.tables.update', $table) }}" method="POST" class="bg-white rounded-xl border border-coffee-100 p-6 shadow-sm">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-coffee-700 mb-1">Nomor Meja</label>
                        <input type="text" name="table_number" value="{{ old('table_number', $table->table_number) }}" required
                               class="w-full border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500">
                    </div>
                    <div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" {{ $table->is_active ? 'checked' : '' }}
                                   class="rounded border-coffee-300 text-coffee-600 focus:ring-coffee-500">
                            <span class="text-sm text-coffee-700">Aktif</span>
                        </label>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <a href="{{ route('admin.tables.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-semibold transition">Batal</a>
                    <button type="submit" class="bg-coffee-700 hover:bg-coffee-800 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">Update</button>
                </div>
            </form>

            <div class="bg-white rounded-xl border border-coffee-100 p-6 shadow-sm">
                <h3 class="font-semibold text-coffee-700 mb-4">QR Code Meja</h3>
                <p class="text-sm text-coffee-500 mb-4">Token: <code class="bg-coffee-50 px-2 py-1 rounded text-xs">{{ $table->qr_code_token }}</code></p>
                <a href="{{ route('admin.tables.qr', $table) }}" target="_blank"
                   class="inline-block bg-coffee-600 hover:bg-coffee-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition mb-4">
                    Lihat & Download QR Code
                </a>
                <form action="{{ route('admin.tables.regenerate', $table) }}" method="POST"
                      onsubmit="return confirm('Generate ulang QR Code? QR lama tidak akan valid lagi.')">
                    @csrf
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                        Generate Ulang Token
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
