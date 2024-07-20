<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
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
Route::post('/login', [AuthController::class, 'login']);

// route group user are required to log in
Route::middleware('auth:sanctum')->group(function () {
    // route current
    Route::get('/me',[AuthController::class, 'me']);

    // create user
    Route::post('/user', [UserController::class, 'store'])
    ->middleware(['AbleCreateUser']);

    // route detail order
    Route::get('/detail-order', function() {
        return 'detail-order';
    });

    // route create order
    Route::get('/create-order', function() {
        return 'create-order';
    })->middleware(['AbleCreateOrder']);

    // route finish order
    Route::get('/finish-order', function() {
        return 'finish-order';
    })->middleware(['AbleFinishOrder']);

    // create item
    Route::post('/item', [ItemController::class])
    ->middleware(['AbleCreateUpdateItem']);
});

