<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tests\PositionController;
use App\Http\Controllers\PaginationController;
use App\Http\Controllers\UserInvitationController;
use Lab404\Impersonate\Controllers\ImpersonateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', []);
})->name('welcome');

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/vuetify', function () {
    return Inertia::render('Vuetify/ShowRoom');
})->name('vuetify');

Route::get('/geo', function () {
    return Inertia::render('Geolocation');
})->name('geo');

Route::resource('position', PositionController::class);



Route::get('/app-layout', function () {
    return Inertia::render('Vuetify/Tests/AppLayout', []);
})->name('test.app.layouts');



Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');




Route::group([
    'middleware' => ['auth', 'is_active', 'verified']
], function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Change pagination perPage  
    Route::post('/pagination', [PaginationController::class, 'update'])
        ->name('pagination.update');
});

Route::get('logout', [ImpersonateController::class, 'logout'])->name('admin.impersonate.logout');

Route::get('/profile', function () {
    return Inertia::render('Welcome', []);
})->name('profile');
Route::get('/profile/edit', function () {
    return Inertia::render('Welcome', []);
})->name('profile.edit');





require __DIR__ . '/manager.php';

require __DIR__ . '/admin.php';
require __DIR__ . '/seller.php';

require __DIR__ . '/auth.php';


// Route::get('register/request', [UserInvitationController::class, 'show'])
//     ->name('user.invitations.show');

// Route::post('invitations', [UserInvitationController::class, 'store'])->middleware('guest')
//     ->name('user.invitations.store');
