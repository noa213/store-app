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

    Route::get('/fetch/userinfo/{id}', [UserController::class, 'fetchUserInfo']);
    Route::post('/decode/token', [UserController::class, 'decodeToken']);
    Route::put('/change/role/{id}', [UserController::class, 'changeRole']);
    Route::get('/role/admin', [UserController::class, 'getRoleAdmin']);
    Route::get('/role/user', [UserController::class, 'getRoleUser']);
    Route::get('/role/auth', [UserController::class, 'getRoleAuthUser']);
});

Route::prefix('drinks')->group(function () {
    Route::post('/', [DrinkController::class, 'store']);    
    Route::get('/', [DrinkController::class, 'index']);       
    Route::get('/{id}', [DrinkController::class, 'show']);   
    Route::put('/{id}', [DrinkController::class, 'update']); 
    Route::delete('/{id}', [DrinkController::class, 'destroy']); 
    Route::get('/user/{userId}', [DrinkController::class, 'getByUserId']);
});
