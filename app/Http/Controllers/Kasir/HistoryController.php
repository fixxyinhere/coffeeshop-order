<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items', 'table', 'confirmedBy'])
            ->whereIn('status', ['completed', 'cancelled']);

        // Filter by date
        $date = $request->get('date', today()->format('Y-m-d'));
        $query->whereDate('created_at', $date);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $totalRevenue = $orders->where('status', 'completed')->sum('total');
        $totalTransactions = $orders->where('status', 'completed')->count();

        return view('kasir.history', compact('orders', 'totalRevenue', 'totalTransactions', 'date'));
    }
}
