<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts', [PostController::class, 'show']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::patch('/posts', [PostController::class, 'update']);
    Route::delete('/posts', [PostController::class, 'destroy']);

    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::delete('/users', [AuthController::class, 'deleteUser']);
});

Route::middleware('guest')->group(function () {

    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

});
