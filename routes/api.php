<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

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