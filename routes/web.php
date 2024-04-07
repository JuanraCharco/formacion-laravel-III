<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use Illuminate\Http\RedirectResponse;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::middleware(['auth'])->group(function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('products', ProductController::class);

    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::get('getPermissions', 'App\Http\Controllers\PermissionController@getPermissions')->name('getPermissions');
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::get('getRoles', 'App\Http\Controllers\RoleController@getRoles')->name('getRoles');
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::get('getUsers', 'App\Http\Controllers\UserController@getUsers')->name('getUsers');

    //Route::get('fileManager', [App\Http\Controllers\FileManagerController::class, 'index'])->name('fileManager');
});
