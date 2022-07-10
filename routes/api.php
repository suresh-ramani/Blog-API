<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register',  [UserController::class,'register']);
Route::post('/login',  [UserController::class,'login']);

Route::group(['middleware'=>['auth:sanctum']], function () {
    Route::apiResource('posts',PostController::class);
    Route::get('/posts/search/{name}',[PostController::class,'search']);

    Route::apiResource('categories',CategoryController::class);
    Route::get('/categories/search/{name}',[CategoryController::class,'search']);
    Route::post('/logout',  [UserController::class,'logout']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
