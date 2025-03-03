<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

    /**
     * Exibe os detalhes de um usuário específico.
     *
     * @param User $user O objeto do usuário a ser exibido.
     * @return JsonResponse
     *
     * Exemplo de requisição:
     * GET /api/users/{id}
     * 
     * Resposta:
     * {
     *   "status": true,
     *   "message": {
     *     "id": 1,
     *     "name": "Altamiro",
     *     "email": "altamiro@email.com",
     *     "created_at": "2024-02-27T12:00:00.000000Z",
     *     "updated_at": "2024-02-27T12:00:00.000000Z"
     *   }
     * }
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $user,
        ], 200);
    }

        /**
     * Cria um novo usuário.
     *
     * @param Request $request Os dados da requisição contendo nome, e-mail e senha.
     * @return JsonResponse
     *
     * Exemplo de requisição:
     * POST /api/create-user
     * 
     * Corpo da requisição (JSON):
     * {
     *   "name": "Altamiro",
     *   "email": "altamiro@email.com",
     *   "password": "123456"
     * }
     * 
     * Resposta de sucesso:
     * {
     *   "status": true,
     *   "message": "Usuário criado com sucesso!",
     *   "user": {
     *     "id": 1,
     *     "name": "Altamiro",
     *     "email": "altamiro@email.com",
     *     "created_at": "2024-02-27T12:00:00.000000Z",
     *     "updated_at": "2024-02-27T12:00:00.000000Z"
     *   }
     * }
     * 
     * Resposta de erro (exemplo de erro de validação):
     * {
     *   "status": false,
     *   "message": "Erro de validação.",
     *   "errors": {
     *     "name": ["O nome deve ter pelo menos 4 caracteres."],
     *     "email": ["Este e-mail já está cadastrado."],
     *     "password": ["A senha deve ter pelo menos 6 caracteres."]
     *   }
     * }
     */

    public function store(Request $request): JsonResponse
     {
        try {
            // Valida os dados recebidos
            $validatedData = $request->validate([
                'name' => 'required|string|min:4|max:100',
                'email' => 'required|string|email|unique:users,email|min:9',
                'password' => 'required|string|min:6',
            ], [
                'name.required' => 'O nome é obrigatório.',
                'name.min' => 'O nome deve ter pelo menos :min caracteres.',
                'email.required' => 'O e-mail é obrigatório.',
                'email.email' => 'O e-mail deve ser um endereço válido.',
                'email.unique' => 'Este e-mail já está cadastrado.',
                'password.required' => 'A senha é obrigatória.',
                'password.min' => 'A senha deve ter pelo menos :min caracteres.',
            ]);
    
            // Cria o usuário
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
            ]);
    
            return response()->json([
                'status'=>true,
                'message' => 'Usuário criado com sucesso!',
                'user' => $user
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status'=>false,
                'message' => 'Erro de validação.',
                'errors' => $e->errors()
            ], 422);
        }
    }


       /**
     * Atualiza os dados de um usuário existente.
     *
     * @param Request $request Os dados da requisição contendo nome e e-mail.
     * @param User $user O usuário a ser atualizado.
     * @return JsonResponse
     *
     * Exemplo de requisição:
     * PUT /api/users/{id}
     * 
     * Corpo da requisição (JSON):
     * {
     *   "name": "Altamiro Silva",
     *   "email": "altamiro.novo@email.com"
     * }
     * 
     * Resposta de sucesso:
     * {
     *   "status": true,
     *   "message": "Usuário atualizado com sucesso!",
     *   "user": {
     *     "id": 1,
     *     "name": "Altamiro Silva",
     *     "email": "altamiro.novo@email.com",
     *     "created_at": "2024-02-27T12:00:00.000000Z",
     *     "updated_at": "2024-02-28T14:00:00.000000Z"
     *   }
     * }
     * 
     * Resposta de erro (exemplo de erro de validação):
     * {
     *   "status": false,
     *   "message": "Erro de validação.",
     *   "errors": {
     *     "name": ["O nome deve ter pelo menos 4 caracteres."],
     *     "email": ["Este e-mail já está cadastrado."]
     *   }
     * }
     */
    public function update(Request $request, User $user): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|min:4|max:100',
                'email' => 'required|string|email|unique:users,email,' . $user->id, // Permite manter o mesmo e-mail
            ], [
                'name.required' => 'O nome é obrigatório.',
                'name.min' => 'O nome deve ter pelo menos :min caracteres.',
                'email.required' => 'O e-mail é obrigatório.',
                'email.email' => 'O e-mail deve ser um endereço válido.',
                'email.unique' => 'Este e-mail já está cadastrado.',
            ]);

            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Usuário atualizado com sucesso!',
                'user' => $user
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro de validação.',
                'errors' => $e->errors()
            ], 422);
        }
    }

        /**
     * Remove um usuário do sistema.
     *
     * @param User $user O usuário a ser excluído.
     * @return JsonResponse Retorna uma resposta JSON indicando o sucesso ou falha da operação.
     *
     * Exemplo de requisição:
     * DELETE /api/users/{id}
     *
     * Respostas possíveis:
     * 1. Sucesso (Usuário encontrado e deletado):
     * {
     *   "status": true,
     *   "message": "Usuário deletado com sucesso!",
     *   "user": {
     *     "id": 1,
     *     "name": "Altamiro",
     *     "email": "altamiro@email.com",
     *     "created_at": "2024-02-27T12:00:00.000000Z",
     *     "updated_at": "2024-02-27T12:00:00.000000Z"
     *   }
     * }
     *
     * 2. Erro (Usuário não encontrado):
     * {
     *   "message": "No query results for model [App\\Models\\User] 9999"
     * }
     * Código de status: 404 (Not Found)
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Usuário deletado com sucesso!',
            'user' => $user
        ], 200);
    }
}
