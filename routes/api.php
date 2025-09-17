<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\AuthController;


// Route::middleware('auth:sanctum')->group(function() {
    
//     Route::post('loans', [LoanController::class, 'store']);
//     Route::get('loans/{userId}', [LoanController::class, 'index']);
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('books', BookController::class);
    Route::post('loans', [LoanController::class, 'store']);
    Route::get('loans/{userId}', [LoanController::class, 'index']);
});


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


