<x-layouts.admin>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-coffee-800">Pengguna</h2>
            <a href="{{ route('admin.users.create') }}" class="bg-coffee-700 hover:bg-coffee-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                + Tambah Pengguna
            </a>
        </div>

        <div class="bg-white rounded-xl border border-coffee-100 overflow-hidden shadow-sm">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-coffee-50 text-coffee-700 text-xs uppercase tracking-wider">
                        <th class="text-left px-4 py-3">Nama</th>
                        <th class="text-left px-4 py-3">Email</th>
                        <th class="text-center px-4 py-3">Role</th>
                        <th class="text-center px-4 py-3">Status</th>
                        <th class="text-center px-4 py-3">Konfirmasi</th>
                        <th class="text-right px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-coffee-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-coffee-50 transition">
                            <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-coffee-500">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-center capitalize">
                                <span class="text-xs font-semibold px-2 py-1 rounded-full
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs font-semibold px-2 py-1 rounded-full
                                    {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center text-coffee-500">{{ $user->confirmed_payments_count }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-coffee-600 hover:text-coffee-800 text-sm font-medium mr-3">Edit</a>
                                @if ($user->role !== 'admin' || $users->where('role', 'admin')->count() > 1)
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</x-layouts.admin>
