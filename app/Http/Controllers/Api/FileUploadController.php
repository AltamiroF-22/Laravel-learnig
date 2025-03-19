<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class FileUploadController extends Controller
{
    /**
     * Retorna a lista de arquivos paginada.
     *
     * @return JsonResponse
     * 
     * Exemplo de requisição:
     * GET /api/files?page=1
     * 
     * Resposta:
     * {
     *   "status": true,
     *   "message": {
     *     "current_page": 1,
     *     "data": [
     *       {
     *         "id": 1,
     *         "user_id": 1,
     *         "filename": "file1.jpg",
     *         "path": "uploads/file1.jpg",
     *         "mime_type": "image/jpeg",
     *         "created_at": "2024-02-27T12:00:00.000000Z",
     *         "updated_at": "2024-02-27T12:00:00.000000Z"
     *       }
     *     ],
     *     "total": 50,
     *     "per_page": 10,
     *     "last_page": 5,
     *     "next_page_url": "http://127.0.0.1:8000/api/files?page=2"
     *   }
     * }
     */
    public function index(): JsonResponse
    {
        $files = File::orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'status' => true,
            'message' => $files
        ], 200);
    }

    /**
     * Retorna os arquivos de um usuário específico.
     *
     * @param int $userId ID do usuário
     * @return JsonResponse
     * 
     * Exemplo de requisição:
     * GET /api/users/{userId}/files
     * 
     * Resposta:
     * {
     *   "status": true,
     *   "message": "Arquivos encontrados",
     *   "files": [
     *     {
     *       "id": 1,
     *       "user_id": 1,
     *       "filename": "file1.jpg",
     *       "path": "uploads/file1.jpg",
     *       "mime_type": "image/jpeg",
     *       "created_at": "2024-02-27T12:00:00.000000Z",
     *       "updated_at": "2024-02-27T12:00:00.000000Z"
     *     }
     *   ]
     * }
     */
    public function getUserFiles($userId): JsonResponse
    {
        // Buscar o usuário pelo ID
        $user = User::find($userId);

        // Se o usuário não existir
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        // Buscar todos os arquivos desse usuário
        $files = $user->files;  // Usando o relacionamento files() definido no modelo User

        // Retornar os arquivos encontrados
        return response()->json([
            'status' => true,
            'message' => 'Arquivos encontrados',
            'files' => $files,  // Retorna os arquivos do usuário
        ]);
    }

    /**
     * Realiza o upload de um novo arquivo.
     *
     * @param Request $request Os dados da requisição contendo o arquivo.
     * @return JsonResponse
     *
     * Exemplo de requisição:
     * POST /api/upload-file
     * 
     * Corpo da requisição (multipart/form-data):
     * {
     *   "img": <arquivo>
     * }
     * 
     * Resposta de sucesso:
     * {
     *   "status": true,
     *   "message": "Upload realizado com sucesso!",
     *   "file": {
     *     "id": 1,
     *     "user_id": 1,
     *     "filename": "file1.jpg",
     *     "path": "uploads/file1.jpg",
     *     "mime_type": "image/jpeg",
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
     *     "img": ["Imagem é obrigatória!", "Somente arquivos jpg, png, pdf e gif são permitidos!", "O arquivo não pode exceder 2MB!"]
     *   }
     * }
     */
    public function store(Request $request): JsonResponse
    {
        // Validação do arquivo
        $request->validate([
            'img' => 'required|mimes:jpg,png,pdf,gif|max:2048', // Tipos e tamanho do arquivo
        ], [
            'img.required' => 'Imagem é obrigatória!',
            'img.mimes' => 'Somente arquivos jpg, png, pdf e gif são permitidos!',
            'img.max' => 'O arquivo não pode exceder 2MB!',
        ]);

        // Obter o usuário autenticado
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado'], 401);
        }

        try {
            // Verificar se um arquivo foi enviado
            if ($request->hasFile('img') && $request->file('img')->isValid()) {
                $file = $request->file('img');
                $path = $file->store('uploads'); // Salva o arquivo no diretório 'uploads'

                // Criar registro no banco de dados
                $fileRecord = File::create([
                    'user_id' => $user->id,
                    'filename' => $file->getClientOriginalName(), // Nome do arquivo
                    'path' => $path, // Caminho do arquivo
                    'mime_type' => $file->getClientMimeType(), // Tipo MIME do arquivo
                ]);

                // Retornar sucesso
                return response()->json([
                    'status' => true,
                    'message' => 'Upload realizado com sucesso!',
                    'file' => $fileRecord,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Erro no upload do arquivo.',
                ], 400);
            }
        } catch (\Exception $e) {
            // Caso ocorra algum erro
            return response()->json([
                'status' => false,
                'message' => 'Erro ao realizar upload: ' . $e->getMessage(),
            ], 500);
        }
    }
}