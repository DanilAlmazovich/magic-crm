<?php

use App\Http\Controllers\API\FCMController;
use App\Http\Controllers\API\Partners\OrdersController;
use App\Http\Controllers\API\Partners\ProductReportsController;
use App\Http\Controllers\API\Partners\TransactionController;
use App\Http\Controllers\API\Partners\WalletController;
use App\Http\Controllers\API\Partners\WorkSchedulesController;
use App\Http\Controllers\Dictionaries\Schemes\WorkSchedulesController as CrmWorkSchedulesController;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('menu/{branch}', [OrdersController::class, 'index'])->name('menu-list');
    Route::get('detail/{order}/{branch}', [OrdersController::class, 'show'])->name('show');
    Route::get('history/{branch}', [OrdersController::class, 'ordersHistory'])->name('history-list');
    Route::post('{branch}/accept/{order}', [OrdersController::class, 'accept'])->name('accept-order');
    Route::post('{branch}/cancel/{order}', [OrdersController::class, 'cancel'])->name('cancel-order');
    Route::post('{branch}/complete/{order}', [OrdersController::class, 'complete'])->name('complete-order');
    Route::post('{branch}/{order}/comment', [OrdersController::class, 'comment'])->name('comment');
});
// Billing
Route::get('/branch/{branch}/wallet', WalletController::class);
Route::get('/branch/{branch}/transactions', TransactionController::class);
Route::get('/branch/{branchId}/products', ProductReportsController::class);

Route::post('{branch}/set-fcm-token', [FCMController::class, 'setFcmTokenForBranch'])->name('set-fcm-token');

Route::prefix('work-schedules')->name('work-schedules.')->group(function () {
    Route::put('{branch}/{work_schedule}', [WorkSchedulesController::class, 'update'])->name('update');
    Route::delete('delete/work-hour/{work_hour}', [WorkSchedulesController::class, 'destroyWorkHour'])->name('destroy-work-hour');
    Route::get('{branch}', [WorkSchedulesController::class, 'show'])->name('show');
    Route::put('multiple/delete/work-hours', [CrmWorkSchedulesController::class, 'multipleDelete'])->name('multiple-delete-work-hours');
});

Route::prefix('order-items')->name('order-items.')->group(function () {
    Route::put('update/{branch}/{order_branch}', [OrdersController::class, 'updateOrderItems'])
        ->name('update');
});
