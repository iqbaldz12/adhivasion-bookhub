<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('books', BookController::class)->except(['show']);
    Route::post('/loans', [LoanController::class, 'store']);
    // routes/api.php
Route::middleware('auth:sanctum')->get('/loans', [\App\Http\Controllers\Api\LoanController::class, 'mine']);
    Route::get('/loans/{user}', [LoanController::class, 'index']);
});
