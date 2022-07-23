<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
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
// for admin
Route::prefix('admin')->middleware(['auth:sanctum','isAdmin'])->group(function (){
    Route::get('/posts',[AdminController::class,'showPosts']);
    Route::delete('/posts/{post}',[AdminController::class,'deletePost']);
    Route::get('/users',[AdminController::class,'showUsers']);
    Route::delete('/users/{user}',[AdminController::class,'deleteUser']);
});

//for user
Route::group(['middleware'=>['auth:sanctum']], function () {
    //post Api
    Route::apiResource('posts',PostController::class);
    Route::get('/posts/search/{name}',[PostController::class,'search']);
    //Comment Api
    Route::post('/posts/{slug}/comments/create',[CommentController::class,'store']);
    Route::get('/posts/{slug}/comments',[CommentController::class,'index']);
    Route::put('/posts/{comment_id}/update',[CommentController::class,'update']);
    Route::delete('/posts/{comment_id}/delete',[CommentController::class,'destroy']);
    //Likes Api
    Route::post('/posts/{post}/toggle-like',[PostController::class,'toggle_like']);
    //Categories Api
    Route::apiResource('categories',CategoryController::class);
    Route::get('/categories/search/{name}',[CategoryController::class,'search']);
    //logout Api
    Route::post('/logout',  [UserController::class,'logout']);
    //Profile Api
    Route::put('/profile/change-password',[ProfileController::class,'change_password']);
    Route::post('/profile/update-profile',[ProfileController::class,'update_profile']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


