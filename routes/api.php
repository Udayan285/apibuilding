<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Api building route...
Route::get('/{id?}',[AuthController::class, 'getAllTodos']);
Route::post('/create',[AuthController::class, 'createTodo'])->middleware('auth:sanctum');
Route::post('/register-user',[AuthController::class, 'registerUser']);
Route::post('/login-user',[AuthController::class, 'login']);
