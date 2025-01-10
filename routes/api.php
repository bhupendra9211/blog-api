<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UsersController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/',function(){
    return "api integrated";
});
Route::post('users/register',[UsersController::class,'userRegistration']);
Route::post('users/login',[UsersController::class,'userLogin']);
