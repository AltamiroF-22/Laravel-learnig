<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users', function (Request $request) {
    return response()->json([
        'status'=>true,
        'message' => 'Listar usuários'
    ],200);
    
});
//})->middleware('auth:sanctum');