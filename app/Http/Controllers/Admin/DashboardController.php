<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();

        // Stat cards
        $totalRevenue = Order::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total');

        $totalTransactions = Order::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();

        $averageOrder = $totalTransactions > 0
            ? $totalRevenue / $totalTransactions
            : 0;

        // Best selling item today
        $bestSeller = OrderItem::select('menu_item_name', DB::raw('SUM(quantity) as total_qty'))
            ->whereHas('order', function ($q) use ($today) {
                $q->whereDate('created_at', $today)->where('status', '!=', 'cancelled');
            })
            ->groupBy('menu_item_name')
            ->orderBy('total_qty', 'desc')
            ->first();

        // Revenue chart last 7 days
        $revenueChart = collect(range(6, 0))->map(function ($day) {
            $date = now()->subDays($day)->format('Y-m-d');
            $revenue = Order::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total');
            return [
                'date' => now()->subDays($day)->format('d M'),
                'revenue' => (float) $revenue,
            ];
        });

        // Peak hours chart (today)
        $peakHours = collect(range(7, 22))->map(function ($hour) use ($today) {
            $count = Order::whereDate('created_at', $today)
                ->whereTime('created_at', '>=', sprintf('%02d:00:00', $hour))
                ->whereTime('created_at', '<', sprintf('%02d:00:00', $hour + 1))
                ->count();
            return [
                'hour' => sprintf('%02d:00', $hour),
                'orders' => $count,
            ];
        });

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalTransactions',
            'averageOrder',
            'bestSeller',
            'revenueChart',
            'peakHours'
        ));
    }
}
