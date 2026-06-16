<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::withCount(['orders' => function ($q) {
            $q->whereDate('created_at', today());
        }])->orderBy('table_number')->paginate(10);

        return view('admin.tables.index', compact('tables'));
    }

    public function create()
    {
        return view('admin.tables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:10|unique:tables,table_number',
            'is_active' => 'boolean',
        ]);

        Table::create([
            'table_number' => strtoupper($validated['table_number']),
            'qr_code_token' => Str::random(40),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil ditambahkan.');
    }

    public function edit(Table $table)
    {
        return view('admin.tables.edit', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:10|unique:tables,table_number,' . $table->id,
            'is_active' => 'boolean',
        ]);

        $table->update([
            'table_number' => strtoupper($validated['table_number']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Table $table)
    {
        if ($table->orders()->whereIn('status', ['pending', 'processing', 'ready'])->count() > 0) {
            return back()->with('error', 'Meja tidak bisa dihapus karena masih ada pesanan aktif.');
        }

        $table->delete();

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil dihapus.');
    }

    public function regenerateToken(Table $table)
    {
        $table->update([
            'qr_code_token' => Str::random(40),
        ]);

        return back()->with('success', 'Token QR Code meja ' . $table->table_number . ' berhasil digenerate ulang.');
    }

    public function qrCode(Table $table)
    {
        return view('admin.tables.qr', compact('table'));
    }
}
