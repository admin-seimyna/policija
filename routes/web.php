<?php

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

Route::get('/', function() {
    return view('app');
});

Route::group(['prefix' => 'settings', 'name' => 'settings.'], function() {

    // User groups
    Route::group(['prefix' => 'user-group', 'name' => 'user-group.'], function() {
        Route::get('list', [\App\Http\Controllers\UserGroupController::class, 'view'])->name('pagination');
        Route::get('payload', [\App\Http\Controllers\UserGroupController::class, 'payload'])->name('payload');
        Route::get('pagination', [\App\Http\Controllers\UserGroupController::class, 'pagination'])->name('pagination');
        Route::post('/',[\App\Http\Controllers\UserGroupController::class, 'create'])->name('create');
        Route::put('/{userGroup}',[\App\Http\Controllers\UserGroupController::class, 'update'])->name('update');
    });

    // Users
    Route::group(['prefix' => 'user', 'name' => 'user.'], function() {
        Route::get('list', [\App\Http\Controllers\UserController::class, 'view'])->name('pagination');
        Route::get('payload', [\App\Http\Controllers\UserController::class, 'payload'])->name('payload');
        Route::get('pagination', [\App\Http\Controllers\UserController::class, 'pagination'])->name('pagination');
        Route::post('/',[\App\Http\Controllers\UserController::class, 'create'])->name('create');
        Route::put('/{user}',[\App\Http\Controllers\UserController::class, 'update'])->name('update');
    });

    // Shifts
    Route::group(['prefix' => 'shift', 'name' => 'shift.'], function() {
        Route::get('list', [\App\Http\Controllers\ShiftController::class, 'view'])->name('pagination');
        Route::get('payload', [\App\Http\Controllers\ShiftController::class, 'payload'])->name('payload');
        Route::get('pagination', [\App\Http\Controllers\ShiftController::class, 'pagination'])->name('pagination');
        Route::post('/',[\App\Http\Controllers\ShiftController::class, 'create'])->name('create');
        Route::put('/{user}',[\App\Http\Controllers\ShiftController::class, 'update'])->name('update');
    });
});
