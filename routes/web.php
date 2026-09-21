<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Console\AppManagementController;
use App\Http\Controllers\Console\LogController;
use App\Http\Controllers\Console\ToolController;
use Illuminate\Support\Facades\Route;

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route('console.apps.index');
    });

    Route::prefix('console')->name('console.')->group(function () {
        // App Management
        Route::resource('apps', AppManagementController::class);
        Route::post('apps/{id}/rotate-key', [AppManagementController::class, 'rotateKey'])->name('apps.rotate-key');

        // Logs & Statistics
        Route::get('logs', [LogController::class, 'index'])->name('logs.index');
        Route::get('statistics', [LogController::class, 'statistics'])->name('logs.statistics');

        // Tools
        Route::get('tools/test', [ToolController::class, 'testTool'])->name('tools.test');
        Route::post('tools/execute', [ToolController::class, 'executeTest'])->name('tools.execute');
    });
});