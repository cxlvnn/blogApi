<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/posts', [ PostController::class, 'index' ]);
Route::get('/posts', [ PostController::class, 'show' ]);
Route::post('/posts', [ PostController::class, 'store' ]);
Route::patch('/posts', [ PostController::class, 'update' ]);
Route::delete('/posts', [ PostController::class, 'destroy' ]);
