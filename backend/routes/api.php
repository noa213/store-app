<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']);    
    Route::get('/', [UserController::class, 'index']);       
    Route::get('/{id}', [UserController::class, 'show']);   
    Route::put('/{id}', [UserController::class, 'update']); 
    Route::delete('/{id}', [UserController::class, 'destroy']); 
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']); 
    Route::get('/{id}', [CategoryController::class, 'show']);   
    Route::post('/', [CategoryController::class, 'store']);    
    Route::put('/{id}', [CategoryController::class, 'update']); 
    Route::delete('/{id}', [CategoryController::class, 'destroy']); 
});

