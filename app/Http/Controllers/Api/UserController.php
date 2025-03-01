<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class UserController
 * 
 * Controller responsável por gerenciar os usuários via API.
 */
class UserController extends Controller
{
    /**
     * Retorna a lista de usuários paginada.
     *
     * @return JsonResponse
     * 
     * Exemplo de requisição:
     * GET /api/users?page=1
     * 
     * Resposta:
     * {
     *   "status": true,
     *   "message": {
     *     "current_page": 1,
     *     "data": [
     *       {
     *         "id": 1,
     *         "name": "Altamiro",
     *         "email": "altamiro@email.com",
     *         "created_at": "2024-02-27T12:00:00.000000Z",
     *         "updated_at": "2024-02-27T12:00:00.000000Z"
     *       }
     *     ],
     *     "total": 50,
     *     "per_page": 10,
     *     "last_page": 5,
     *     "next_page_url": "http://127.0.0.1:8000/api/users?page=2"
     *   }
     * }
     */
    public function index(): JsonResponse
    {
        $users = User::orderBy('id', 'DESC')->paginate(10);
        
        return response()->json([
            'status' => true,
            'message' => $users
        ], 200);
    }
}
