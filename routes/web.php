<?php

use App\Http\Controllers\Billing\OperationController;
use App\Http\Controllers\Billing\Reports\OperatorsConsolidatedReportController;
use App\Http\Controllers\Billing\Reports\OperatorsDetailedReportController;
use App\Http\Controllers\Billing\Reports\PartnerReportController;
use App\Http\Controllers\Billing\WalletController;
use App\Http\Controllers\BlacklistsController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\CourierOrdersController;
use App\Http\Controllers\CouriersController;
use App\Http\Controllers\Dictionaries\OperatorsController;
use App\Http\Controllers\Dictionaries\Schemes\ConstantCourierPaymentsController;
use App\Http\Controllers\Dictionaries\Schemes\DynamicCommissionCalculationsController;
use App\Http\Controllers\Dictionaries\Schemes\PartnerDeliveriesController;
use App\Http\Controllers\Dictionaries\Schemes\PaymentMethodsController;
use App\Http\Controllers\Dictionaries\Schemes\WorkSchedulesController;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\OrderAddressesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\OrderStatusesController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\PersonalCourierOrdersController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StandardOrdersController;
use Domain\Users\Enums\Role;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest'])->get('/', function () {
    return redirect('/login');
});

Route::group([
    'middleware' => ['auth:sanctum'],
], function () {
    Route::name('couriers')->prefix('couriers')
        ->group(function () {
            Route::get('', [CouriersController::class, 'index'])->name('.index');
            Route::get('{courier}', [CouriersController::class, 'show'])->name('.view');
            Route::post('', [CouriersController::class, 'store'])->name('.store');
            Route::put('{courier}', [CouriersController::class, 'update'])->name('.update');
            Route::delete('{courier}', [CouriersController::class, 'destroy'])->name('.destroy');
            Route::get('get/active', [CouriersController::class, 'activeCouriers'])->name('.active');
            Route::get('on/map', [CouriersController::class, 'couriersOnMap'])->name('.on-map');
            Route::post('{courier}/block', [CouriersController::class, 'block'])->name('.block');
            Route::post('{courier}/activate', [CouriersController::class, 'activate'])->name('.activate');
        });

    Route::get('couriers-for-select', [CouriersController::class, 'getForSelect'])
        ->name('couriers.get-for-select');
    Route::get('operators-for-select', [OperatorsController::class, 'getForSelect'])
        ->name('operators.get-for-select');

    Route::name('partners')
        ->middleware('role:' . implode(',', [Role::ADMIN]))
        ->group(function () {
            Route::get('partners', [PartnersController::class, 'index'])->name('.index');
            Route::get('partners-for-select', [PartnersController::class, 'getForSelect'])
                ->name('.get-for-select');
            Route::get('partners/{partner}', [PartnersController::class, 'show'])->name('.view');
            Route::post('partners', [PartnersController::class, 'store'])->name('.store');
            Route::put('partners/{partner}', [PartnersController::class, 'update'])->name('.update');
            Route::delete('partners/{partner}', [PartnersController::class, 'destroy'])->name('.destroy');
            Route::delete('partners/{partner}/branch/{branch}', [PartnersController::class, 'destroyBranch'])->name('.branch.destroy');
            Route::delete('partners/{partner}/file/{file}', [PartnersController::class, 'fileDestroy'])->name('.file.destroy');
            Route::post('partners/{partner}/files', [PartnersController::class, 'storeFile'])->name('.files.store');
            Route::post('partners/check-schedule', [ScheduleController::class, 'check'])->name('.check-schedule');
        });

    Route::name('orders')->prefix('orders')->group(function () {
        Route::get('create/standard', [OrdersController::class, 'renderStandardOrderCreateForm'])
            ->name('.create.standard');
        Route::get('create/personal', [OrdersController::class, 'renderPersonalOrderCreateForm'])
            ->name('.create.personal');
        Route::get('', [OrdersController::class, 'index'])->name('.list');
        Route::get('{order}', [OrdersController::class, 'show'])->name('.show');
        Route::get('{order}/statuses', [OrdersController::class, 'getStatuses'])->name('.statuses');
        Route::delete('{order}', [OrdersController::class, 'delete'])->name('.delete');

        Route::put('{order}', [StandardOrdersController::class, 'update'])->name('.update.standard');
        Route::post('', [StandardOrdersController::class, 'store'])->name('.store.standard');

        Route::post('personal', [PersonalCourierOrdersController::class, 'store'])->name('.store.personal');
        Route::put('personal/{order}', [PersonalCourierOrdersController::class, 'update'])->name('.update.personal');
        Route::post('{order}/cancel', [OrderStatusesController::class, 'cancel'])->name('.cancel-order');
        Route::post('{order}/transfer-to-partners', [OrderStatusesController::class, 'transferToPartners'])->name('.transfer-to-partners');
        Route::post('{order}/courier/{courier}', [CourierOrdersController::class, 'assignCourier'])->name('.assign-courier');

        Route::post('{order}/addresses', [OrderAddressesController::class, 'store'])->name('.addresses.store');
        Route::put('{order}/addresses/{address}', [OrderAddressesController::class, 'update'])->name('.addresses.update');
        Route::delete('{order}/addresses/{address}', [OrderAddressesController::class, 'remove'])->name('.addresses.remove');
        Route::post('{order}/accept-canceling', [OrderStatusesController::class, 'acceptCourierCanceling'])
            ->name('.accept-courier-cancel-the-order');
    });

    Route::name('dictionaries.')
        ->prefix('dictionaries')
        ->middleware('role:' . implode(',', [Role::ADMIN]))
        ->group(function () {
            Route::resource('operators', OperatorsController::class)
                ->only('index', 'store', 'update', 'destroy');

            Route::get('/operators-for-select', [OperatorsController::class, 'getForSelect']);

            Route::inertia('/establishments', 'Directory/Establishments');
            Route::inertia('/delivery-promotions', 'Directory/DeliveryPromotions');
            Route::resource('partner-delivery-schemes', PartnerDeliveriesController::class);
            Route::resource('constant-courier-payment-schemes', ConstantCourierPaymentsController::class)
                ->only('index', 'store', 'update', 'destroy');
            Route::resource('dynamic-commission-calculations', DynamicCommissionCalculationsController::class)
                ->except(['create', 'edit']);
            Route::delete('dynamic-commission-calculations/surcharge/{surcharge}', [DynamicCommissionCalculationsController::class, 'deleteSurcharge']);
            Route::delete('dynamic-commission-calculations/sumPercentageToOffice/{sumPercentageToOffice}', [DynamicCommissionCalculationsController::class, 'deleteSumPercentageToOffice']);
            Route::resource('work-schedule', WorkSchedulesController::class)
                ->except(['create', 'edit', 'show']);
            Route::resource('payment-methods', PaymentMethodsController::class)
                ->except(['show', 'edit', 'create']);

            Route::prefix('work-schedules')->name('work-schedules.')->group(function () {
                Route::get('', [WorkSchedulesController::class, 'getSchedules'])->name('index');
                Route::get('{work_schedule}', [WorkSchedulesController::class, 'show'])->name('show');
                Route::delete('delete/work-hour/{work_hour}', [WorkSchedulesController::class, 'destroyWorkHour'])
                    ->name('destroy-work-hour');
                Route::put('multiple/delete/work-hours', [WorkSchedulesController::class, 'multipleDelete'])
                    ->name('multiple-delete-work-hours');
            });
        });

    Route::name('dictionaries.')
        ->prefix('dictionaries')
        ->group(function () {
            Route::get('payment-methods/get-active-methods', [PaymentMethodsController::class, 'getActiveMethods'])
                ->name('payment-methods.getActiveMethods');
        });

    Route::post('maps/geocode', [MapsController::class, 'geocode']);

    Route::get('clients', [ClientsController::class, 'index'])->name('clients');
    Route::get('clients/check/number', [ClientsController::class, 'checkNumber'])->name('clients.check-number');
    Route::put('clients/{client}', [ClientsController::class, 'update']);

    Route::prefix('blacklist')
        ->name('blacklist.')
        ->middleware('role:' . implode(',', [Role::ADMIN, Role::COURIER_OPERATOR]))
        ->group(function () {
            Route::get('', [BlacklistsController::class, 'index'])->name('index');
            Route::post('', [BlacklistsController::class, 'store'])->name('store');
            Route::delete('{blacklist}', [BlacklistsController::class, 'destroy'])->name('destroy');
            Route::get('check/number', [BlacklistsController::class, 'checkNumberExists'])->name('check-number');
        });

    Route::inertia('/billing/orders', 'Billing/Orders');

    Route::name('billing')->prefix('billing')->group(function () {
        Route::middleware('role:admin')
            ->group(function () {
                Route::get('/operations', [OperationController::class, 'index'])
                    ->name('.operations.index');
                Route::post('/operations', [OperationController::class, 'store'])
                    ->name('.operations.store');
                Route::post('/operations/{operationId}/cancel', [OperationController::class, 'cancel'])
                    ->name('.operations.cancel');

                Route::get('/wallets', [WalletController::class, 'index'])
                    ->name('.wallets.index');
                Route::get('wallets-list', [WalletController::class, 'getWalletsList'])
                    ->name('.wallets.list');

                Route::get('/partner-report', [PartnerReportController::class, 'index'])
                    ->name('.partner-report');
                Route::get('/partner-report/export', [PartnerReportController::class, 'export']);
            });

        Route::get('/detailed-report', [OperatorsDetailedReportController::class, 'index'])
            ->name('.detailed-report');
        Route::get('/detailed-report/export', [OperatorsDetailedReportController::class, 'exportToXls'])
            ->name('detailed-report.export');
        Route::get('/consolidated-report', [OperatorsConsolidatedReportController::class, 'index'])
            ->name('.consolidated-report');
        Route::get('/consolidated-report/export', [OperatorsConsolidatedReportController::class, 'exportToXls'])->name('consolidated-report');
    });
    Route::inertia('/billing/product-report', 'Billing/PartnerProductReport')
        ->name('.product-report');

    Route::inertia('/fines', 'Fines');
    Route::inertia('/partners-remarks', 'PartnersRemarks');

    Route::post('fcm-token', [FcmController::class, 'store']);
});

Route::webhooks('webhooks/orders', 'orders');
Route::webhooks('webhooks/courier-orders', 'courier-orders');
