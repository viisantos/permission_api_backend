<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::middleware('api')->group(function () {
    Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);
    Route::post('login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::get('roles/{role}/add-permissions', [App\Http\Controllers\RoleController::class, 'addPermissionToRole'])->name('roles.add-permissions');
    Route::put('roles/{role}/give-permissions', [App\Http\Controllers\RoleController::class, 'givePermissionToRole'])->name('roles.give-permissions');
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::get('user', [App\Http\Controllers\AuthController::class, 'user']);
});
