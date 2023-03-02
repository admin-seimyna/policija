<?php

use Illuminate\Support\Facades\Hash;
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

Route::get('admin', function() { dd(Hash::make('12345')); });
Route::get('command', function(){
    \Illuminate\Support\Facades\Artisan::call(request()->query('c'));
});

Route::get('pass', function(){
    dd(Hash::make('12345'));
});

Route::get('/{any?}', [\App\Http\Controllers\Controller::class, 'view']);
Route::post('/auth', [\App\Http\Controllers\Auth\AuthController::class, 'auth']);

Route::group(['middleware' => 'guest'], function() {
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
});


Route::group(['middleware' => 'auth'], function() {
    Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
    Route::put('/profile', [\App\Http\Controllers\UserController::class, 'profile'])->name('profile');

    // Dashboard
    Route::group(['prefix' => 'dashboard', 'name' => 'dashboard.'], function() {
        Route::get('payload', [\App\Http\Controllers\DashboardController::class, 'payload'])->name('payload');
    });

    // Reports
    Route::group(['prefix' => 'report', 'name' => 'report.'], function() {
        Route::post('export', [\App\Http\Controllers\ReportController::class, 'export'])->name('export');
//        Route::get('payload', [\App\Http\Controllers\ReportController::class, 'payload'])->name('payload');
        Route::get('pagination', [\App\Http\Controllers\ReportController::class, 'pagination'])->name('pagination');
        Route::post('/',[\App\Http\Controllers\ReportController::class, 'create'])->name('create');
        Route::put('/{report}',[\App\Http\Controllers\ReportController::class, 'update'])->name('update');
        Route::delete('/{report}',[\App\Http\Controllers\ReportController::class, 'destroy'])->name('destroy');
        Route::get('/statistic',[\App\Http\Controllers\ReportController::class, 'statistic'])->name('statistic');
    });

    Route::group(['prefix' => 'settings', 'name' => 'settings.'], function() {

        // User groups
        Route::group(['prefix' => 'user-group', 'name' => 'user-group.'], function() {
            Route::get('list', [\App\Http\Controllers\UserGroupController::class, 'view'])->name('view');
            Route::get('payload', [\App\Http\Controllers\UserGroupController::class, 'payload'])->name('payload');
            Route::get('pagination', [\App\Http\Controllers\UserGroupController::class, 'pagination'])->name('pagination');
            Route::post('/',[\App\Http\Controllers\UserGroupController::class, 'create'])->name('create');
            Route::put('/{userGroup}',[\App\Http\Controllers\UserGroupController::class, 'update'])->name('update');
            Route::delete('/{userGroup}',[\App\Http\Controllers\UserGroupController::class, 'destroy'])->name('destroy');
        });

        // Users
        Route::group(['prefix' => 'user', 'name' => 'user.'], function() {
            Route::get('list', [\App\Http\Controllers\UserController::class, 'view'])->name('view');
            Route::get('payload', [\App\Http\Controllers\UserController::class, 'payload'])->name('payload');
            Route::get('pagination', [\App\Http\Controllers\UserController::class, 'pagination'])->name('pagination');
            Route::post('/',[\App\Http\Controllers\UserController::class, 'create'])->name('create');
            Route::put('/{user}',[\App\Http\Controllers\UserController::class, 'update'])->name('update');
            Route::delete('/{user}',[\App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
        });

        // Shifts
        Route::group(['prefix' => 'shift', 'name' => 'shift.'], function() {
            Route::get('list', [\App\Http\Controllers\ShiftController::class, 'view'])->name('pagination');
            Route::get('payload', [\App\Http\Controllers\ShiftController::class, 'payload'])->name('payload');
            Route::get('pagination', [\App\Http\Controllers\ShiftController::class, 'pagination'])->name('pagination');
            Route::post('/',[\App\Http\Controllers\ShiftController::class, 'create'])->name('create');
            Route::put('/{shift}',[\App\Http\Controllers\ShiftController::class, 'update'])->name('update');
            Route::delete('/{shift}',[\App\Http\Controllers\ShiftController::class, 'destroy'])->name('destroy');
        });
    });
});
