<x-layouts.admin>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-heading text-2xl text-coffee-800">Kategori</h2>
            <a href="{{ route('admin.categories.create') }}" class="bg-coffee-700 hover:bg-coffee-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                + Tambah Kategori
            </a>
        </div>

        <div class="bg-white rounded-xl border border-coffee-100 overflow-hidden shadow-sm">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-coffee-50 text-coffee-700 text-xs uppercase tracking-wider">
                        <th class="text-left px-4 py-3">Nama</th>
                        <th class="text-left px-4 py-3">Slug</th>
                        <th class="text-center px-4 py-3">Urutan</th>
                        <th class="text-center px-4 py-3">Status</th>
                        <th class="text-right px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-coffee-100">
                    @foreach ($categories as $cat)
                        <tr class="hover:bg-coffee-50 transition">
                            <td class="px-4 py-3 font-medium">{{ $cat->name }}</td>
                            <td class="px-4 py-3 text-coffee-500">{{ $cat->slug }}</td>
                            <td class="px-4 py-3 text-center">{{ $cat->sort_order }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs font-semibold px-2 py-1 rounded-full
                                    {{ $cat->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $cat->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.categories.edit', $cat) }}" class="text-coffee-600 hover:text-coffee-800 text-sm font-medium mr-3">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $categories->links() }}</div>
    </div>
</x-layouts.admin>
