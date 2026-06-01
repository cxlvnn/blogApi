<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {

        // author
        Route::get('/author/{name}', [AuthorController::class, 'getAuthor'])->where('name', '[A-Za-z]+');

        // posts
        Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
        Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
        Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
        Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::post('/posts/{post}/bookmark', [BookmarkController::class, 'saveOrUnsave']);

        // comments
        Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('comments.index');
        Route::get('/posts/{post}/comments/{comment}', [CommentController::class, 'show'])->name('comments.show');
        Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
        Route::patch('/posts/{post}/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
        Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

        // like
        Route::get('/posts/{post}/likes', [LikeController::class, 'index']);
        Route::post('/posts/{post}/like', [LikeController::class, 'likeOrUnlike']);

        // bookmarks
        Route::get('/me/bookmarks', [BookmarkController::class, 'indexForThree']);

        // user related
        Route::get('/user', function () {
            return response()->json(['message' => 'Authenticated.'], 200);
        });
        Route::post('/user', [UserController::class, 'update']);
        Route::get('/me', [AuthController::class, 'me'])->name('me');
        Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::delete('/user', [AuthController::class, 'deleteUser']);

    });

});

Route::middleware('web', 'guest')->group(function () {

    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

});
