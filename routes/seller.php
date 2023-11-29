<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Seller\StockController;
use App\Http\Controllers\Seller\PlanningController;
use App\Http\Controllers\Seller\PunchingController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\InventoryController;
use App\Http\Controllers\Seller\SalePointController;

Route::group([
  'middleware' => ['auth', 'is_active', 'verified', 'role:seller|manager|Super Admin'],
  'prefix' => 'seller',
  'as' => 'seller.',
], function () {

  Route::get('/', DashboardController::class)->name('dashboard');

  // Route::get('punchings', [PunchingController::class, 'index'])->name('punchings.index');
  // Route::post('punchings', [PunchingController::class, 'store'])->name('punchings.store');

  // Route::get('sale_points', [SalePointController::class, 'index'])->name('sale_points.index');

  // Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');

  // Route::post('stocks/inventory', [InventoryController::class, 'store'])->name('inventory.store');

  

  // PLANNINGS VISU
  // Route::get('/plannings/calendar/{month?}', [PlanningController::class, 'index'])->name('plannings.calendar.index');
});
