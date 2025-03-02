<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class LoginController
 * 
 * Controlador responsável pela autenticação de usuários via API.
 */
class LoginController extends Controller
{
    /**
     * Realiza a autenticação do usuário e retorna um token de acesso.
     *
     * @param Request $request Contém as credenciais do usuário (email e senha).
     * @return \Illuminate\Http\JsonResponse Retorna um JSON com o status da autenticação, token e dados do usuário.
     *
     * Exemplo de requisição:
     * POST /api/login
     *
     * Corpo da requisição (JSON):
     * {
     *   "email": "altamiro@email.com",
     *   "password": "123456"
     * }
     *
     * Resposta de sucesso:
     * {
     *   "status": true,
     *   "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsIn...",
     *   "user": {
     *     "id": 1,
     *     "name": "Altamiro",
     *     "email": "altamiro@email.com",
     *     "created_at": "2024-02-27T12:00:00.000000Z",
     *     "updated_at": "2024-02-27T12:00:00.000000Z"
     *   },
     *   "message": "Você está logado."
     * }
     *
     * Resposta de erro (credenciais inválidas):
     * {
     *   "status": false,
     *   "message": "Login ou senha inválida."
     * }
     */
    public function login(Request $request)
    {
        // Verifica se o usuário forneceu credenciais corretas
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Obtém os dados do usuário autenticado
            $user = Auth::user();

            // Gera um token de API para o usuário autenticado
            $token = $request->user()->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'token' => $token,
                'user' => $user,
                'message' => 'Você está logado.'
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Login ou senha inválida.'
            ], 404);
        }
    }
}
