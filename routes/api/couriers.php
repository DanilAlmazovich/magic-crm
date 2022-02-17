<?php

use App\Http\Controllers\API\Couriers\Auth\AuthenticateCouriersController;
use App\Http\Controllers\API\Couriers\CourierOrdersController;
use App\Http\Controllers\API\Couriers\CouriersController;
use App\Http\Controllers\API\Couriers\TransactionController;
use App\Http\Controllers\API\Couriers\WalletController;
use App\Http\Controllers\API\FCMController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthenticateCouriersController::class, 'loginCourier'])
    ->middleware('throttle:logins')
    ->name('login');

Route::middleware(['auth:courier', 'courier.available'])->group(function () {
    Route::get('courier', [AuthenticateCouriersController::class, 'getAuthenticatedCourier'])
        ->name('authenticated');
    Route::post('place', [CouriersController::class, 'setPlace'])
        ->name('place');
    Route::get('orders', [CourierOrdersController::class, 'index'])->name('orders');
    Route::get('orders/{order}', [CourierOrdersController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/accept', [CourierOrdersController::class, 'acceptOrder'])->name('orders.accept-order');
    Route::put('orders/{order}/order-address/{orderAddress}/status', [CourierOrdersController::class, 'changeOrderAddressStatus'])
        ->name('orders.orderAddress.change-status');
    Route::get('orderAddresses/{orderAddress}/orderItems', [CourierOrdersController::class, 'getOrderItemsByPickupAddress'])->name('orders.getOrderItemsByPickupAddress');
    Route::post('orders/{order}/cancel', [CourierOrdersController::class, 'cancelOrder'])->name('orders.cancel-order');
    Route::post('orders/{order}/deliver', [CourierOrdersController::class, 'deliverOrder'])->name('orders.deliver-order');
    Route::post('set-fcm-token', [FCMController::class, 'setFcmTokenForCourier'])->name('set-fcm-token');

    Route::get('wallet', [WalletController::class, 'showCurrentUserWallet'])->name('show-current-user-wallet');
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
});
