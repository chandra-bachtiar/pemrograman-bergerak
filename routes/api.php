<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('validate', [AuthController::class, 'validateToken']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'produk'
], function ($router) {
    Route::get('/', [ProdukController::class, 'index']);
    Route::get('/{id}', [ProdukController::class, 'show']);
    Route::post('/', [ProdukController::class, 'store']);
    Route::put('/{id}', [ProdukController::class, 'update']);
    Route::delete('/{id}', [ProdukController::class, 'destroy']);
});
