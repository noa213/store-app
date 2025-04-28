<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
