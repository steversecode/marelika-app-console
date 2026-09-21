<?php

use App\Http\Controllers\Api\OdooGatewayController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/odoo')->group(function () {
    Route::get('/orders', [OdooGatewayController::class, 'getOrders']);
    Route::post('/orders/{id}/mark-synced', [OdooGatewayController::class, 'markSynced']);
    Route::post('/orders/{id}/update-tracking', [OdooGatewayController::class, 'updateTracking']);
});