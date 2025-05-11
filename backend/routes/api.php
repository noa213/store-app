<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DrinkController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/refresh', [AuthController::class, 'refreshToken']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']);    
    Route::get('/', [UserController::class, 'index']);       
    Route::get('/{id}', [UserController::class, 'show']);   
    Route::put('/{id}', [UserController::class, 'update']); 
    Route::delete('/{id}', [UserController::class, 'destroy']); 

    Route::get('/fetch/userinfo', [UserController::class, 'fetchUserInfo']);
    Route::post('/decode/token', [UserController::class, 'decodeToken']);
    Route::put('/role/{id}', [UserController::class, 'changeRole']);
    Route::get('admin', [UserController::class, 'getRoleAdmin']);
    Route::get('/role', [UserController::class, 'getRoleUser']);
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

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']); 
    Route::get('/{id}', [CategoryController::class, 'show']);   
    Route::post('/', [CategoryController::class, 'store']);    
    Route::put('/{id}', [CategoryController::class, 'update']); 
    Route::delete('/{id}', [CategoryController::class, 'destroy']); 
});
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']); 
    Route::get('/{id}', [ProductController::class, 'show']);   
    Route::get('/by-user/{userId}', [ProductController::class, 'showByUserId']);   
    Route::post('/', [ProductController::class, 'store']);    
    Route::put('/{id}', [ProductController::class, 'update']); 
    Route::delete('/{id}', [ProductController::class, 'destroy']); 
});

