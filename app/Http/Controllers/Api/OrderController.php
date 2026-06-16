<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function newOrders(): JsonResponse
    {
        $orders = Order::with(['items.options', 'table'])
            ->whereIn('status', ['pending', 'processing', 'ready'])
            ->orderBy('created_at')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'table_number' => $order->table->table_number,
                    'status' => $order->status,
                    'subtotal' => (float) $order->subtotal,
                    'total' => (float) $order->total,
                    'notes' => $order->notes,
                    'created_at_human' => $order->created_at->diffForHumans(),
                    'created_at' => $order->created_at->format('H:i'),
                    'items' => $order->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'menu_item_name' => $item->menu_item_name,
                            'menu_item_price' => (float) $item->menu_item_price,
                            'quantity' => $item->quantity,
                            'subtotal' => (float) $item->subtotal,
                            'notes' => $item->notes,
                            'options' => $item->options->map(function ($opt) {
                                return [
                                    'option_group' => $opt->option_group,
                                    'option_value' => $opt->option_value,
                                    'price_modifier' => (float) $opt->price_modifier,
                                ];
                            }),
                        ];
                    }),
                ];
            });

        $pendingCount = Order::where('status', 'pending')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'pending_count' => $pendingCount,
                'orders' => $orders,
            ],
            'message' => 'OK',
        ]);
    }

    public function status(Order $order): JsonResponse
    {
        $order->load('items.options', 'table');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'status_label' => $order->status_label,
                'table_number' => $order->table->table_number,
                'total' => (float) $order->total,
                'formatted_total' => $order->formatted_total,
                'items' => $order->items->map(function ($item) {
                    return [
                        'menu_item_name' => $item->menu_item_name,
                        'quantity' => $item->quantity,
                        'menu_item_price' => (float) $item->menu_item_price,
                        'subtotal' => (float) $item->subtotal,
                        'notes' => $item->notes,
                        'options' => $item->options->map(function ($opt) {
                            return [
                                'option_group' => $opt->option_group,
                                'option_value' => $opt->option_value,
                            ];
                        }),
                    ];
                }),
                'created_at' => $order->created_at->format('H:i'),
                'created_at_human' => $order->created_at->diffForHumans(),
            ],
            'message' => 'OK',
        ]);
    }
}
