<x-layouts.admin>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-heading text-2xl text-coffee-800">Menu</h2>
            <a href="{{ route('admin.menu.create') }}" class="btn-primary">
                + Tambah Menu
            </a>
        </div>

        <div class="bg-white rounded-xl border border-coffee-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-coffee-50 text-coffee-700 text-xs uppercase tracking-wider">
                            <th class="text-left px-4 py-3">Foto</th>
                            <th class="text-left px-4 py-3">Nama</th>
                            <th class="text-left px-4 py-3">Kategori</th>
                            <th class="text-right px-4 py-3">Harga</th>
                            <th class="text-center px-4 py-3">Status</th>
                            <th class="text-right px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-coffee-100">
                        @foreach ($menuItems as $item)
                            <tr class="hover:bg-coffee-50 transition">
                                <td class="px-4 py-3">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-12 h-9 rounded object-cover">
                                </td>
                                <td class="px-4 py-3 font-medium">{{ $item->name }}</td>
                                <td class="px-4 py-3 text-coffee-500">{{ $item->category->name }}</td>
                                <td class="px-4 py-3 text-right">{{ $item->formatted_price }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full
                                        {{ $item->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $item->is_available ? 'Tersedia' : 'Habis' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.menu.edit', $item) }}" class="text-coffee-600 hover:text-coffee-800 text-sm font-medium mr-3">Edit</a>
                                    <form action="{{ route('admin.menu.destroy', $item) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus menu {{ $item->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $menuItems->links() }}
        </div>
    </div>
</x-layouts.admin>
