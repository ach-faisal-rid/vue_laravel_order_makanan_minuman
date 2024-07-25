<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
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

// route login
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

// route group user are required to log in
Route::middleware(['auth:sanctum'])->group(function () {
    // route current
    Route::get('/me',[AuthController::class, 'me'])->name('me');

    // create user
    Route::post('/user', [UserController::class, 'store'])
    ->middleware(['AbleCreateUser'])->name('create-user');

    // route detail order
    Route::get('/detail-order', [OrderController::class, 'index'])
    ->name('detail-order');

    // route detail order berdasarkan id
    Route::get('/detail-order/{id}', [OrderController::class, 'show'])
    ->name('show-order');

    // route create order
    Route::post('/order', [OrderController::class, 'store'])
    ->middleware(['AbleCreateOrder'])
    ->name('create-order');

    // route done order
    Route::get('/done-order/{id}/set-as-done', [OrderController::class, 'setAsDone'])
    ->middleware(['AbleFinishOrder'])
    ->name('done-order');

    // route payment order
    Route::get('/done-order/{id}/payment', [OrderController::class, 'payment'])
    ->middleware(['AblePayOrder'])
    ->name('pay-order');

    // route index item
    Route::get('/item', [ItemController::class, 'index'])->name('index');

    // route create item
    Route::post('/item', [ItemController::class, 'store'])
    ->middleware(['AbleCreateUpdateItem'])->name('create-item');

    // route update item
    Route::post('/item/{id}', [ItemController::class, 'update'])
    ->middleware(['AbleCreateUpdateItem'])->name('update-item');
});
