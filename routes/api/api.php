<?php

use App\Http\Controllers\CouriersController;
use App\Http\Controllers\Dictionaries\Schemes\WorkSchedulesController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\PersonalCourierOrdersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('orders/personal', [PersonalCourierOrdersController::class, 'store'])->name('api.order.store.personal');

Route::post('maps/geocode', [MapsController::class, 'geocode']);
Route::post('maps/delivery-cost', [MapsController::class, 'deliveryCost']);
Route::post('maps/personal-delivery-cost', [MapsController::class, 'personalDeliveryCost']);
Route::get('tracking/{courier}', [CouriersController::class, 'show'])->name('tracking-courier');

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('work-schedules')->name('work-schedule.')->group(function () {
        Route::get('{work_schedule}', [WorkSchedulesController::class, 'show'])->name('api.show');
        Route::delete('delete/work-hour/{work_hour}', [WorkSchedulesController::class, 'destroyWorkHour'])->name('destroy-work-hour');
        Route::put('multiple/delete/work-hours', [WorkSchedulesController::class, 'multipleDelete'])
            ->name('multiple-delete-work-hours');
        Route::get('', [WorkSchedulesController::class, 'getSchedules'])->name('work-schedules-api-list');
    });
});
