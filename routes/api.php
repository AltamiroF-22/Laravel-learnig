<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\FavoriteProductController;
use App\Http\Controllers\Api\FileUploadController;
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
    Route::post('/products/{product}/favorite', [FavoriteProductController::class, 'store']);  // POST http://127.0.0.1:8000/api/products/22/favorite
    Route::delete('/products/{product}/favorite', [FavoriteProductController::class, 'destroy']);// POST http://127.0.0.1:8000/api/products/12/favorite
    Route::get('/favorites', [FavoriteProductController::class, 'index']); // GET http://127.0.0.1:8000/api/favorite?page=2
});

//⚠️ Não tem relação com as rotas acima
//Appointments
Route::post('/appointments', [AppointmentController::class, 'store']);// POST http://127.0.0.1:8000/api/appointments
Route::get('/appointments', [AppointmentController::class, 'index']);// GET http://127.0.0.1:8000/api/appointments?date=2025-03-11


// Upload File
Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::post('/uploadFile',[FileUploadController::class, 'store']); //POST http://127.0.0.1:8000/api/uploadFile
});
Route::get('/files',[FileUploadController::class, 'index']); //GET http://127.0.0.1:8000/api/uploadFile
Route::get('/user/{userId}/files', [FileUploadController::class, 'getUserFiles']); //GET http://127.0.0.1:8000/api/user/22/files