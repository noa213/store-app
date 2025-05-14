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

Route::prefix('users')->middleware(['auth:api', 'role:admin|superadmin'])->group(function () {
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

Route::prefix('drinks')->middleware('auth:api')->group(function () {
    Route::post('/', [DrinkController::class, 'store']);    
    Route::get('/', [DrinkController::class, 'index']);       
    Route::get('/{id}', [DrinkController::class, 'show']);   
    Route::put('/{id}', [DrinkController::class, 'update']); 
    Route::delete('/{id}', [DrinkController::class, 'destroy']); 
    Route::get('/user/{userId}', [DrinkController::class, 'getByUserId']);
});

Route::controller(CategoryController::class)
    ->prefix('/categories')
    ->middleware(['auth:api'])
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
});

Route::controller(CategoryController::class)
    ->prefix('/categories')
    ->middleware(['auth:api', 'role:admin|superadmin'])
    ->group(function () {
        Route::post('/', 'store');
        Route::put('/', 'update');
        Route::delete('/{id}', 'destroy');
});

Route::prefix('products')->middleware('auth:api')->group(function () {
    Route::get('/', [ProductController::class, 'index']); 
    Route::get('/{id}', [ProductController::class, 'show']);   
    Route::get('/by-user/{userId}', [ProductController::class, 'showByUserId']);   
    Route::post('/', [ProductController::class, 'store']);    
    Route::put('/{id}', [ProductController::class, 'update']); 
});

Route::prefix('products')->middleware(['auth:api', 'role:admin|superadmin'])->group(function () {
    Route::delete('/{id}', [ProductController::class, 'destroy']); 
});
