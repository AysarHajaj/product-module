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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get(
    'unauthenticated',
    [AuthController::class, 'unauthenticated']
)->name('unauthenticated');

Route::middleware('auth:api')->group(function () {
    Route::get(
        'logout',
        [AuthController::class, 'logout']
    );
});
