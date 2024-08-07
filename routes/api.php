<?php

use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Post\PostController;
use App\Http\Controllers\Auth\{LoginController,RegisterController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
//--------------------------------Auth Routes -------------------------------------
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
//--------------------------------Auth Routes -------------------------------------


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class);
    Route::apiResource('categories', CategoryController::class);
});
