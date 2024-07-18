<?php

use App\Http\Controllers\AuthController;
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

Route::post('/login', [AuthController::class, 'login']);

Route::get('/detail-order', function() {
    return 'detail-order';
})->middleware('auth:sanctum');

Route::get('/create-order', function() {
    return 'create-order';
})->middleware('auth:AbleCreateOrder');

Route::get('/finish-order', function() {
    return 'finish-order';
})->middleware('auth:AbleFinishOrder');

