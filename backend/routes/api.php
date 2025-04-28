<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DrinkController;

Route::get('/categories', [CategoryController::class, 'fetchCategoriesList']);

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']);    
    Route::get('/', [UserController::class, 'index']);       
    Route::get('/{id}', [UserController::class, 'show']);   
    Route::put('/{id}', [UserController::class, 'update']); 
    Route::delete('/{id}', [UserController::class, 'destroy']); 
});

Route::prefix('drinks')->group(function () {
    Route::post('/', [DrinkController::class, 'store']);    
    Route::get('/', [DrinkController::class, 'index']);       
    Route::get('/{id}', [DrinkController::class, 'show']);   
    Route::put('/{id}', [DrinkController::class, 'update']); 
    Route::delete('/{id}', [DrinkController::class, 'destroy']); 
    Route::get('/user/{userId}', [DrinkController::class, 'getByUserId']);
});
