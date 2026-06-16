<x-layouts.admin>
    <div>
        <h2 class="text-2xl font-bold text-coffee-800 mb-6">Edit Pengguna: {{ $user->name }}</h2>
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white rounded-xl border border-coffee-100 p-6 shadow-sm max-w-lg">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-coffee-700 mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-700 mb-1">Password <span class="text-coffee-400 font-normal">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" minlength="6"
                           class="w-full border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-700 mb-1">Role</label>
                    <select name="role" required class="w-full border-coffee-200 rounded-lg focus:border-coffee-500 focus:ring-coffee-500">
                        <option value="kasir" {{ $user->role === 'kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}
                               class="rounded border-coffee-300 text-coffee-600 focus:ring-coffee-500">
                        <span class="text-sm text-coffee-700">Aktif</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-semibold transition">Batal</a>
                <button type="submit" class="bg-coffee-700 hover:bg-coffee-800 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">Update</button>
            </div>
        </form>
    </div>
</x-layouts.admin>
