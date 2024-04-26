<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\SubscriptionsController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\BarbersController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('users', UsersController::class);
Route::apiResource('subscriptions', SubscriptionsController::class);
Route::apiResource('services', ServicesController::class);
Route::apiResource('barbers', BarbersController::class);