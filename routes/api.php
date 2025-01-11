<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UsersController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\CommentController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('/',[UsersController::class,'test']);
Route::post('users/register',[UsersController::class,'userRegistration']);
Route::post('users/login',[UsersController::class,'userLogin']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('posts', [PostController::class, 'index']);
    Route::post('posts', [PostController::class, 'store']);
    Route::get('posts/{post}', [PostController::class, 'show']);
    Route::put('posts/{post}', [PostController::class, 'update']);
    Route::delete('posts/{post}', [PostController::class, 'destroy']);

    Route::get('posts/{post}/comments', [CommentController::class, 'index']);
    Route::post('posts/{post}/comments', [CommentController::class, 'store']);
    Route::put('comments/{comment}', [CommentController::class, 'update']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
});
Route::post('posts/{post}/images', [PostController::class, 'uploadImage'])->middleware('auth:sanctum');
Route::delete('posts/{post}/images', [PostController::class, 'deleteImage'])->middleware('auth:sanctum');


