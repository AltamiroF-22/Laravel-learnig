<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index () {
        $users = User::orderBy('id', 'DESC')-> paginate(10);
        return response()->json([
            'status'=> true,
            'message'=> $users
        ],200);
    }
}
