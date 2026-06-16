<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\MenuItemOption;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index(string $token)
    {
        $table = Table::where('qr_code_token', $token)->where('is_active', true)->first();

        if (! $table) {
            return response()->view('errors.table-not-found', [], 404);
        }

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->with(['menuItems' => function ($query) {
                $query->orderBy('sort_order')->orderBy('name');
            }])
            ->get();

        return view('customer.menu', compact('table', 'categories'));
    }

    public function store(Request $request, string $token)
    {
        $table = Table::where('qr_code_token', $token)->where('is_active', true)->firstOrFail();

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1|max:99',
            'items.*.notes' => 'nullable|string|max:500',
            'items.*.options' => 'nullable|array',
            'items.*.options.*.option_group' => 'required_with:items.*.options|string',
            'items.*.options.*.option_value' => 'required_with:items.*.options|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $orderItems = [];

            foreach ($validated['items'] as $itemData) {
                $menuItem = MenuItem::findOrFail($itemData['menu_item_id']);

                if (!$menuItem->is_available) {
                    return response()->json([
                        'success' => false,
                        'message' => "{$menuItem->name} sedang tidak tersedia."
                    ], 422);
                }

                $itemSubtotal = $menuItem->price * $itemData['quantity'];

                // Look up price modifiers from database (server-side validation)
                $optionsPriceModifier = 0;
                $validatedOptions = [];

                if (!empty($itemData['options'])) {
                    foreach ($itemData['options'] as $optionData) {
                        // Find matching option in database
                        $dbOption = MenuItemOption::where('menu_item_id', $menuItem->id)
                            ->where('option_group', $optionData['option_group'])
                            ->where('option_value', $optionData['option_value'])
                            ->first();

                        if ($dbOption) {
                            $optionsPriceModifier += (float) $dbOption->price_modifier;
                            $validatedOptions[] = [
                                'option_group' => $dbOption->option_group,
                                'option_value' => $dbOption->option_value,
                                'price_modifier' => (float) $dbOption->price_modifier,
                            ];
                        }
                    }
                }

                $itemSubtotal += ($optionsPriceModifier * $itemData['quantity']);
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'menu_item_id' => $menuItem->id,
                    'menu_item_name' => $menuItem->name,
                    'menu_item_price' => $menuItem->price,
                    'quantity' => $itemData['quantity'],
                    'subtotal' => $itemSubtotal,
                    'notes' => $itemData['notes'] ?? null,
                    'options' => $validatedOptions,
                ];
            }

            $orderNumber = Order::generateOrderNumber();

            $order = Order::create([
                'table_id' => $table->id,
                'order_number' => $orderNumber,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($orderItems as $itemData) {
                $options = $itemData['options'] ?? [];
                unset($itemData['options']);

                $orderItem = $order->items()->create($itemData);

                foreach ($options as $option) {
                    $orderItem->options()->create([
                        'option_group' => $option['option_group'],
                        'option_value' => $option['option_value'],
                        'price_modifier' => $option['price_modifier'],
                    ]);
                }
            }

            DB::commit();

            session(['order_' . $table->qr_code_token . '_latest' => $order->id]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dikirim!',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'redirect' => route('customer.order.status', ['token' => $token, 'order' => $order->id]),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function status(string $token, Order $order)
    {
        $table = Table::where('qr_code_token', $token)->where('is_active', true)->firstOrFail();

        if ($order->table_id !== $table->id) {
            abort(404);
        }

        $order->load('items.options');

        return view('customer.status', compact('table', 'order'));
    }
}
