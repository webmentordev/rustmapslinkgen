<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'index']);

    Route::get('/maps', [MapController::class, 'index']);
    Route::post('/upload/map', [MapController::class, 'store']);
    Route::delete('/logout', [AuthController::class, 'logout']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);