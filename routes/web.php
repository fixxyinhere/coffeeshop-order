<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\TableController as AdminTableController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Controllers\Customer\MenuController as CustomerMenuController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\HistoryController as KasirHistoryController;
use App\Http\Controllers\Kasir\MenuController as KasirMenuController;
use App\Http\Controllers\Kasir\OrderController as KasirOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// =================== CUSTOMER ROUTES (no auth) ===================
Route::prefix('menu')->group(function () {
    Route::get('{token}', [CustomerMenuController::class, 'index'])->name('customer.menu');
    Route::post('{token}/order', [CustomerMenuController::class, 'store'])->name('customer.order.store');
    Route::get('{token}/order/{order}/status', [CustomerMenuController::class, 'status'])->name('customer.order.status');
});

// =================== KASIR ROUTES ===================
Route::prefix('kasir')->middleware(['auth', 'kasir'])->name('kasir.')->group(function () {
    Route::get('dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');
    Route::post('orders/{order}/accept', [KasirOrderController::class, 'accept'])->name('orders.accept');
    Route::post('orders/{order}/ready', [KasirOrderController::class, 'ready'])->name('orders.ready');
    Route::post('orders/{order}/payment', [KasirOrderController::class, 'confirmPayment'])->name('orders.payment');
    Route::post('orders/{order}/cancel', [KasirOrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('orders/{order}/receipt', [KasirOrderController::class, 'receipt'])->name('orders.receipt');
    Route::post('menu/{item}/toggle', [KasirMenuController::class, 'toggleAvailability'])->name('menu.toggle');
    Route::get('history', [KasirHistoryController::class, 'index'])->name('history');
});

// =================== ADMIN ROUTES ===================
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('menu', AdminMenuController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('tables', AdminTableController::class);
    Route::resource('users', AdminUserController::class);

    Route::post('tables/{table}/regenerate', [AdminTableController::class, 'regenerateToken'])->name('tables.regenerate');
    Route::get('tables/{table}/qr', [AdminTableController::class, 'qrCode'])->name('tables.qr');

    Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [AdminReportController::class, 'export'])->name('reports.export');
});

// =================== API ROUTES ===================
Route::prefix('api')->name('api.')->group(function () {
    Route::get('kasir/new-orders', [ApiOrderController::class, 'newOrders'])->middleware(['auth', 'kasir'])->name('kasir.new-orders');
    Route::get('order/{order}/status', [ApiOrderController::class, 'status'])->name('order.status');
});

require __DIR__.'/auth.php';
