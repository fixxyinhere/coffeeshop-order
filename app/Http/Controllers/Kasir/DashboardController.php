<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $activeOrders = Order::with(['items.options', 'table'])
            ->whereIn('status', ['pending', 'processing', 'ready'])
            ->orderBy('created_at')
            ->get();

        $menuItems = MenuItem::with('category')
            ->orderBy('category_id')
            ->orderBy('sort_order')
            ->get();

        $pendingCount = Order::where('status', 'pending')->count();

        return view('kasir.dashboard', compact('activeOrders', 'menuItems', 'pendingCount'));
    }
}
