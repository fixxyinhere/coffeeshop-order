<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\MenuItemOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::with('category.options')
            ->orderBy('category_id')
            ->orderBy('sort_order')
            ->paginate(15);

        return view('admin.menu.index', compact('menuItems'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available' => 'boolean',
            'sort_order' => 'integer|min:0',
            'options' => 'nullable|array',
            'options.*.option_group' => 'required|string|max:100',
            'options.*.option_value' => 'required|string|max:100',
            'options.*.price_modifier' => 'numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $menuItem = new MenuItem();
            $menuItem->category_id = $validated['category_id'];
            $menuItem->name = $validated['name'];
            $menuItem->slug = Str::slug($validated['name']) . '-' . Str::random(5);
            $menuItem->description = $validated['description'] ?? null;
            $menuItem->price = $validated['price'];
            $menuItem->is_available = $request->boolean('is_available', true);
            $menuItem->sort_order = $validated['sort_order'] ?? 0;

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('menu', 'public');
                $menuItem->image = $path;
            }

            $menuItem->save();

            // Save options
            if (!empty($validated['options'])) {
                foreach ($validated['options'] as $option) {
                    $menuItem->options()->create([
                        'option_group' => $option['option_group'],
                        'option_value' => $option['option_value'],
                        'price_modifier' => $option['price_modifier'] ?? 0,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.menu.index')
                ->with('success', 'Menu berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan menu: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(MenuItem $menu)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $menu->load('options');
        return view('admin.menu.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, MenuItem $menu)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available' => 'boolean',
            'sort_order' => 'integer|min:0',
            'options' => 'nullable|array',
            'options.*.id' => 'nullable|exists:menu_item_options,id',
            'options.*.option_group' => 'required|string|max:100',
            'options.*.option_value' => 'required|string|max:100',
            'options.*.price_modifier' => 'numeric|min:0',
            'deleted_options' => 'nullable|array',
            'deleted_options.*' => 'exists:menu_item_options,id',
        ]);

        try {
            DB::beginTransaction();

            $menu->category_id = $validated['category_id'];
            $menu->name = $validated['name'];
            $menu->description = $validated['description'] ?? null;
            $menu->price = $validated['price'];
            $menu->is_available = $request->boolean('is_available', true);
            $menu->sort_order = $validated['sort_order'] ?? 0;

            if ($request->hasFile('image')) {
                // Delete old image
                if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                    Storage::disk('public')->delete($menu->image);
                }
                $path = $request->file('image')->store('menu', 'public');
                $menu->image = $path;
            }

            $menu->save();

            // Delete removed options
            if (!empty($validated['deleted_options'])) {
                MenuItemOption::whereIn('id', $validated['deleted_options'])->delete();
            }

            // Save/update options
            if (!empty($validated['options'])) {
                foreach ($validated['options'] as $option) {
                    if (!empty($option['id'])) {
                        // Update existing option
                        MenuItemOption::where('id', $option['id'])->update([
                            'option_group' => $option['option_group'],
                            'option_value' => $option['option_value'],
                            'price_modifier' => $option['price_modifier'] ?? 0,
                        ]);
                    } else {
                        // Create new option
                        $menu->options()->create([
                            'option_group' => $option['option_group'],
                            'option_value' => $option['option_value'],
                            'price_modifier' => $option['price_modifier'] ?? 0,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.menu.index')
                ->with('success', 'Menu berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui menu: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(MenuItem $menu)
    {
        if ($menu->image && Storage::disk('public')->exists($menu->image)) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil dihapus.');
    }
}
