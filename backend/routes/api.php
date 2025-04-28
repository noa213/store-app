<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

Route::get('/categories', [CategoryController::class, 'fetchCategoriesList']);

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']);    
    Route::get('/', [UserController::class, 'index']);       
    Route::get('/{id}', [UserController::class, 'show']);   
    Route::put('/{id}', [UserController::class, 'update']); 
    Route::delete('/{id}', [UserController::class, 'destroy']); 
});
