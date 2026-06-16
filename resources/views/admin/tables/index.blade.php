<x-layouts.admin>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-coffee-800">Meja</h2>
            <a href="{{ route('admin.tables.create') }}" class="bg-coffee-700 hover:bg-coffee-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                + Tambah Meja
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            @foreach ($tables as $table)
                <div class="bg-white rounded-xl border border-coffee-100 p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-bold text-lg text-coffee-800">{{ $table->table_number }}</h3>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full
                            {{ $table->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $table->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <p class="text-xs text-coffee-400 mb-3">Transaksi hari ini: {{ $table->orders_count }}</p>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.tables.qr', $table) }}" target="_blank"
                           class="flex-1 text-center bg-coffee-100 hover:bg-coffee-200 text-coffee-700 text-xs font-semibold py-2 rounded-lg transition">
                            QR Code
                        </a>
                        <a href="{{ route('admin.tables.edit', $table) }}"
                           class="flex-1 text-center bg-coffee-600 hover:bg-coffee-700 text-white text-xs font-semibold py-2 rounded-lg transition">
                            Edit
                        </a>
                        <form action="{{ route('admin.tables.destroy', $table) }}" method="POST" class="flex-1"
                              onsubmit="return confirm('Hapus meja {{ $table->table_number }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold py-2 rounded-lg transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $tables->links() }}</div>
    </div>
</x-layouts.admin>
