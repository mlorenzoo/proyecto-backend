<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\SubscriptionsController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\BarbersController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\BarberServiceController;
use App\Http\Controllers\Api\CustomerSubscriptionController;
use App\Http\Controllers\Api\BarbershopsController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ClientsController;
use App\Http\Controllers\Api\UserClientController;

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
Route::apiResource('barbershops', BarbershopsController::class);
Route::apiResource('payments', PaymentController::class);
Route::apiResource('clients', ClientsController::class);
Route::apiResource('userclient', UserClientController::class);
Route::get('/barbers/{id}/schedules', [BarbersController::class, 'getSchedules']);
Route::get('/appointments/available', [AppointmentController::class, 'index']);

// TokenController
Route::middleware('auth:sanctum')->get('/user', [TokenController::class, 'user']);
Route::middleware('guest')->post('/register', [TokenController::class, 'register']);
Route::middleware('guest')->post('/login', [TokenController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [TokenController::class, 'logout']);
