<?php

use App\Http\Controllers\Api\FavoriteProductController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

//User
Route::get('/users',[UserController::class, 'index']); // GET http://127.0.0.1:8000/api/users?page=2
Route::get('/users/{user}',[ UserController::class, 'show']); // GET http://127.0.0.1:8000/api/users/8

Route::post('/users',[ UserController::class, 'store']); // POST http://127.0.0.1:8000/api/users

Route::put('/users/{user}',[ UserController::class, 'update']); // PUT http://127.0.0.1:8000/api/users/22

Route::delete('/users/{user}',[ UserController::class, 'destroy']); // DELETE http://127.0.0.1:8000/api/users/13

Route::post('/login', [LoginController::class,'login'])->name('login'); //POST http://127.0.0.1:8000/api/login

// Rota privada
Route::group(['middleware' => ['auth:sanctum']],function(){

    Route::post('/logout/{user}',[LoginController::class, 'logout']); //POST http://127.0.0.1:8000/api/logout/13

    Route::get('/users-rota-privada',[UserController::class, 'index']); // GET http://127.0.0.1:8000/api/users-rota-privada
});


//Products
Route::get('/products',[ProductController::class,'index']); // GET http://127.0.0.1:8000/api/products?page=2
Route::get('/products/{product}',[ProductController::class,'show']); // GET http://127.0.0.1:8000/api/products/22

Route::post('/products',[ProductController::class,'store']); // POST http://127.0.0.1:8000/api/products/

Route::put('/products/{product}',[ProductController::class,'update']); // PUT http://127.0.0.1:8000/api/products/

Route::delete('/products/{product}',[ProductController::class,'destroy']);// DELETE http://127.0.0.1:8000/api/products/13

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/products/{product}/favorite', [FavoriteProductController::class, 'store']);
    Route::delete('/products/{product}/favorite', [FavoriteProductController::class, 'destroy']);
});
