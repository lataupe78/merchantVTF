<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\UserController;
use App\Http\Controllers\Manager\AssetController;
use App\Http\Controllers\Manager\StockController;
use App\Http\Controllers\Manager\ReportController;
use App\Http\Controllers\Manager\PlanningController;
use App\Http\Controllers\Manager\SettingsController;
use App\Http\Controllers\Manager\AssetUnitController;
use App\Http\Controllers\Manager\DashboardController;
use App\Http\Controllers\Manager\SalePointController;
use App\Http\Controllers\Manager\ReportPeriodController;
use App\Http\Controllers\Manager\WorkingRangeController;
use App\Http\Controllers\Manager\PlanningPeriodController;
use App\Http\Controllers\Manager\PlanningWorkerController;
use App\Http\Controllers\Manager\UserInvitationController;
use App\Http\Controllers\Manager\PlanningPunchingController;
use App\Http\Controllers\Manager\PlanningDashboardController;
use App\Http\Controllers\Manager\PlanningSalePointController;
use App\Http\Controllers\Manager\PlanningValidationController;
use App\Http\Controllers\Manager\PlanningValidationV2Controller;

Route::group([
  'middleware' => ['auth', 'is_active', 'verified', 'role:manager|Super Admin'],
  'prefix' => 'manager',
  'as' => 'manager.',
], function () {

  Route::get('/', DashboardController::class)->name('dashboard');


  // SETTINGS 
  Route::put('/settings/debug', [SettingsController::class, 'debug'])
    ->name('settings.debug.toggle');

  // USERS
  Route::resource('/users', UserController::class);


  // USERS INVIVATIONS

  Route::get('/invitations', [UserInvitationController::class, 'index'])->name('invitations.index');
  Route::post('/invitations', [UserInvitationController::class, 'store'])->name('invitations.store');



  // SALE_POINTS
  Route::resource('/sale_points', SalePointController::class);


  // REPORTS
  Route::get('/reports/month/{month?}', [ReportPeriodController::class, 'month'])->name('reports.month');
  Route::get('/reports/week/{week?}', [ReportPeriodController::class, 'week'])->name('reports.week');
  Route::get('/reports/day/{day?}', [ReportPeriodController::class, 'day'])->name('reports.day');

  Route::resource('/reports', ReportController::class);

  // STOCKS
  Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');

  // ASSETS
  Route::resource('/assets', AssetController::class);
  Route::resource('/asset_units', AssetUnitController::class);


  // PLANNINGS 




  // PLANNINGS VALIDATION

  Route::get('/plannings/validation', [PlanningValidationV2Controller::class, 'index'])->name('plannings.validation.index');

  Route::get('/plannings/validation/{day}', [PlanningValidationV2Controller::class, 'show'])->name('plannings.validation.show');

  Route::post('/plannings/validation/', [PlanningValidationV2Controller::class, 'store'])->name('plannings.validation.store');
  Route::match(['put', 'patch'], '/plannings/validation/{rangeId}', [PlanningValidationV2Controller::class, 'update'])->name('plannings.validation.update');


  // ===========================================
  // PLANNINGS VISU
  //  WIP  nov 2023

  Route::get('/plannings/dashboard', [PlanningDashboardController::class, 'show'])->name('plannings.dashboard');


  // Show month Calendar for all Workers
  Route::get('/plannings/calendar/{month?}', [PlanningController::class, 'index'])->name('plannings.calendar.index');

  
  // Show month Calendar for all Workers
  Route::get('/plannings/punchings', [PlanningPunchingController::class, 'index'])->name('plannings.punchings.index');




  // PLANNINGS WORKERS
  Route::get('/plannings/workers/{month?}', [PlanningWorkerController::class, 'index'])->name('plannings.workers.index');
  Route::get('/plannings/workers/{worker}/calendar/{month?}', [PlanningWorkerController::class, 'show'])->name('plannings.workers.show');

  Route::post('/plannings/workers/{worker}/calendar', [PlanningWorkerController::class, 'storeAll'])->name('plannings.workers.storeAll');


  // PLANNINGS SALE_POINTS
  Route::get('/plannings/sale_points/{month?}', [PlanningSalePointController::class, 'index'])->name('plannings.sale_points.index');
  Route::get('/plannings/sale_points/{sale_point}/calendar/{month?}', [PlanningSalePointController::class, 'show'])->name('plannings.sale_points.show');

  // PLANNINGS VALIDATION
  Route::get('/plannings/validation', [PlanningValidationController::class, 'index'])->name('plannings.validation.index');


  // V2 Sort by User
  Route::get(
    '/plannings/validation/{day}',
    [PlanningValidationV2Controller::class, 'show']
  )->name('plannings.validation.show');

  Route::post('/plannings/validation/', [PlanningValidationController::class, 'store'])->name('plannings.validation.store');
  Route::match(['put', 'patch'], '/plannings/validation/{rangeId}', [PlanningValidationController::class, 'update'])->name('plannings.validation.update');


  // PLANNINGS WORKERS PERIODS
  Route::get('/plannings/period/month/{month?}', [PlanningPeriodController::class, 'month'])->name('plannings.period.month');
  Route::get('/plannings/period/week/{week?}', [PlanningPeriodController::class, 'week'])->name('plannings.period.week');
  Route::get('/plannings/period/day/{day?}', [PlanningPeriodController::class, 'day'])->name('plannings.period.day');


  //=================================

  // OLD ?


  // RANGE VALIDATION
  Route::post('/plannings/range', [WorkingRangeController::class, 'store'])->name('planning.working_ranges.store');
  Route::post('/plannings/ranges', [WorkingRangeController::class, 'storeAll'])->name('planning.working_ranges.storeAll');



  Route::match(['put', 'patch'], '/range/{range}', [WorkingRangeController::class, 'update'])->name('working_ranges.update');

  // ===========================================



  Route::post('/plannings', [PlanningController::class, 'store'])->name('plannings.store');
  Route::match(['put', 'patch'], '/plannings/{planningId}', [PlanningController::class, 'update'])->name('plannings.update');
});
