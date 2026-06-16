<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function accept(Order $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan sudah diproses.');
        }

        $order->update(['status' => 'processing']);

        return back()->with('success', 'Pesanan ' . $order->order_number . ' diterima.');
    }

    public function ready(Order $order)
    {
        if ($order->status !== 'processing') {
            return back()->with('error', 'Pesanan sedang tidak dalam proses.');
        }

        $order->update(['status' => 'ready']);

        return back()->with('success', 'Pesanan ' . $order->order_number . ' siap diambil.');
    }

    public function confirmPayment(Request $request, Order $order)
    {
        if ($order->status !== 'ready') {
            return back()->with('error', 'Pesanan belum siap.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,qris,transfer',
            'cash_received' => 'required_if:payment_method,cash|nullable|numeric|min:0',
        ]);

        $data = [
            'status' => 'completed',
            'payment_method' => $validated['payment_method'],
            'payment_confirmed_at' => now(),
            'payment_confirmed_by' => auth()->id(),
        ];

        if ($validated['payment_method'] === 'cash') {
            $cashReceived = $validated['cash_received'] ?? 0;
            $data['cash_received'] = $cashReceived;
            $data['change_amount'] = $cashReceived - $order->total;
        }

        $order->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran dikonfirmasi.',
            'redirect' => route('kasir.orders.receipt', $order),
        ]);
    }

    public function cancel(Order $order)
    {
        if (in_array($order->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Pesanan sudah selesai atau dibatalkan.');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Pesanan ' . $order->order_number . ' dibatalkan.');
    }

    public function receipt(Order $order)
    {
        $order->load('items.options', 'table', 'confirmedBy');

        return view('kasir.receipt', compact('order'));
    }
}
