<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'today');

        $dates = $this->getDateRange($period, $request);

        $query = Order::whereBetween('created_at', [$dates['start'], $dates['end']])
            ->where('status', 'completed');

        // Payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $orders = $query->with('table', 'confirmedBy')->orderBy('created_at', 'desc')->get();

        // Summary
        $totalRevenue = $orders->sum('total');
        $totalTransactions = $orders->count();
        $averageOrder = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        $totalItemsSold = OrderItem::whereIn('order_id', $orders->pluck('id'))->sum('quantity');

        // Top products
        $topProducts = OrderItem::select('menu_item_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->whereIn('order_id', $orders->pluck('id'))
            ->groupBy('menu_item_name')
            ->orderBy('total_qty', 'desc')
            ->limit(10)
            ->get();

        // Revenue trend
        $revenueTrend = $this->getRevenueTrend($dates['start'], $dates['end']);

        return view('admin.reports.index', compact(
            'orders',
            'totalRevenue',
            'totalTransactions',
            'averageOrder',
            'totalItemsSold',
            'topProducts',
            'revenueTrend',
            'period'
        ));
    }

    public function export(Request $request)
    {
        $period = $request->get('period', 'today');
        $dates = $this->getDateRange($period, $request);

        $query = Order::whereBetween('created_at', [$dates['start'], $dates['end']])
            ->where('status', 'completed');

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $orders = $query->with('table', 'confirmedBy')->orderBy('created_at', 'desc')->get();

        $totalRevenue = $orders->sum('total');
        $totalTransactions = $orders->count();
        $totalItemsSold = OrderItem::whereIn('order_id', $orders->pluck('id'))->sum('quantity');

        $topProducts = OrderItem::select('menu_item_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->whereIn('order_id', $orders->pluck('id'))
            ->groupBy('menu_item_name')
            ->orderBy('total_qty', 'desc')
            ->limit(10)
            ->get();

        $dateRange = $dates['start']->format('d M Y') . ' - ' . $dates['end']->format('d M Y');

        $pdf = Pdf::loadView('admin.reports.export', compact(
            'orders',
            'totalRevenue',
            'totalTransactions',
            'totalItemsSold',
            'topProducts',
            'dateRange',
            'dates'
        ));

        $filename = 'laporan-' . $dates['start']->format('Ymd') . '-' . $dates['end']->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    private function getDateRange(string $period, Request $request): array
    {
        return match ($period) {
            'today' => [
                'start' => today()->startOfDay(),
                'end' => today()->endOfDay(),
            ],
            'week' => [
                'start' => now()->startOfWeek(),
                'end' => now()->endOfWeek(),
            ],
            'month' => [
                'start' => now()->startOfMonth(),
                'end' => now()->endOfMonth(),
            ],
            'custom' => [
                'start' => $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : today()->startOfDay(),
                'end' => $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : today()->endOfDay(),
            ],
            default => [
                'start' => today()->startOfDay(),
                'end' => today()->endOfDay(),
            ],
        };
    }

    private function getRevenueTrend(Carbon $start, Carbon $end): array
    {
        $days = $start->diffInDays($end) + 1;

        return collect(range(0, $days - 1))->map(function ($day) use ($start) {
            $date = $start->copy()->addDays($day);
            $revenue = Order::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total');
            return [
                'date' => $date->format('d M'),
                'revenue' => (float) $revenue,
            ];
        })->toArray();
    }
}
