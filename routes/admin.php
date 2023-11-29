<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SalePointController;
use App\Http\Controllers\Admin\ActivityLogController;
use Lab404\Impersonate\Controllers\ImpersonateController;

Route::group([
  'middleware' => ['auth', 'is_active', 'verified', 'role:admin|Super Admin'],
  'prefix' => 'admin',
  'as' => 'admin.',
], function () {

  Route::get('/', DashboardController::class)->name('dashboard');

  // Route::get('activities', [ActivityLogController::class, 'index'])->name('activities.index');
  // Route::resource('sale_points', SalePointController::class);
  // Route::get('/users', [UserController::class, 'index'])->name('users.index');

  // Route::get('login/{user}', [ImpersonateController::class, 'login'])->name('impersonate.login');
  
});
