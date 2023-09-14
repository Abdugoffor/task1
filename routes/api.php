<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ApplicationController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/registr', [AuthController::class, 'registr']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verified_code', [AuthController::class, 'verified_code'])->middleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//  admin route
Route::middleware(['auth:sanctum', 'checkRole:1'])->group(function () {
    Route::get('/requests', [ApplicationController::class, 'index']);
});

// user route
Route::middleware(['auth:sanctum', 'checkRole:2'])->group(function () {
    Route::get('/test', [ApplicationController::class, 'test']);
    // ...
});