<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users',[UserController::class, 'index']); // GET http://127.0.0.1:8000/api/users?page=2
Route::get('/users/{user}',[ UserController::class, 'show']); // GET http://127.0.0.1:8000/api/users/8

Route::post('/users',[ UserController::class, 'store']); // POST http://127.0.0.1:8000/api/users

Route::put('/users/{user}',[ UserController::class, 'update']); // PUT http://127.0.0.1:8000/api/users/22