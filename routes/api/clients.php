<?php

use App\Http\Controllers\API\Clients\OrdersController;
use App\Http\Controllers\API\FCMController;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('{client:phone}', [OrdersController::class, 'index'])->name('index');
    Route::get('standard/{order}', [OrdersController::class, 'showStandard'])->name('standard-detail');
    Route::get('personal/{order}', [OrdersController::class, 'showPersonalCourier'])->name('personal-detail');
    Route::get('track/{order}', [OrdersController::class, 'trackOrderStatus'])->name('track-order');
    Route::post('{client:phone}/cancel/{order}', [OrdersController::class, 'cancel'])->name('cancel-order');
    Route::post('{client:phone}/review/{order}', [OrdersController::class, 'review'])->name('review-order');
});

Route::post('fcm-token', [FCMController::class, 'setFcmTokenForClient'])->name('set-fcm-token');
