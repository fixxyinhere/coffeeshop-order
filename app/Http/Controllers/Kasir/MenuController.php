<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function toggleAvailability(MenuItem $item)
    {
        $item->update([
            'is_available' => !$item->is_available,
        ]);

        $status = $item->is_available ? 'tersedia' : 'tidak tersedia';

        return response()->json([
            'success' => true,
            'message' => "{$item->name} sekarang {$status}.",
            'data' => [
                'is_available' => $item->is_available,
            ],
        ]);
    }
}
