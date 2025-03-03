<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
   
   /**
 * Exibe uma lista paginada de produtos.
 *
 * @return JsonResponse
 *
 * Exemplo de requisição:
 * GET /api/products
 * 
 * Resposta:
 * {
 *   "status": true,
 *   "message": {
 *     "data": [
 *       {
 *         "id": 1,
 *         "name": "Produto Exemplo 1",
 *         "description": "Descrição do produto 1",
 *         "price": 199.99,
 *         "mainImage": "https://example.com/imagem-principal1.jpg",
 *         "created_at": "2024-02-27T12:00:00.000000Z",
 *         "updated_at": "2024-02-27T12:00:00.000000Z"
 *       },
 *       {
 *         "id": 2,
 *         "name": "Produto Exemplo 2",
 *         "description": "Descrição do produto 2",
 *         "price": 149.99,
 *         "mainImage": "https://example.com/imagem-principal2.jpg",
 *         "created_at": "2024-02-27T12:00:00.000000Z",
 *         "updated_at": "2024-02-27T12:00:00.000000Z"
 *       }
 *     ],
 *     "current_page": 1,
 *     "last_page": 5,
 *     "per_page": 20,
 *     "total": 100
 *   }
 * }
 */
public function index(): JsonResponse
{
    $products = Product::paginate(20);

    return response()->json([
        'status' => true,
        'message' => $products
    ], 200);
}


    /**
    * Cria um novo produto no banco de dados.
    *
    * @param Request $request Os dados do produto a serem salvos.
    * @return JsonResponse
    *
    * Exemplo de requisição:
    * POST /api/products
    * 
    * Corpo da requisição:
    * {
    *   "name": "Produto Exemplo",
    *   "description": "Descrição do produto",
    *   "price": 199.99,
    *   "mainImage": "https://example.com/imagem.jpg",
    *   "images": ["https://example.com/imagem1.jpg"],
    *   "stock": 50,
    *   "category_id": 1
    * }
    * 
    * Resposta (em caso de sucesso):
    * {
    *   "status": true,
    *   "message": "Produto criado com sucesso!",
    *   "product": {
    *     "id": 1,
    *     "name": "Produto Exemplo",
    *     "description": "Descrição do produto",
    *     "price": 199.99,
    *     "mainImage": "https://example.com/imagem.jpg",
    *     "created_at": "2024-02-27T12:00:00.000000Z",
    *     "updated_at": "2024-02-27T12:00:00.000000Z"
    *   }
    * }
    */
    public function store(Request $request): JsonResponse
    {
        try{
            // Validação dos dados
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'mainImage' => 'nullable|url', // A imagem principal deve ser uma URL válida
                'images' => 'nullable|array', // Deve ser um array
                'images.*' => 'url', // Cada item dentro do array deve ser uma URL
                'stock' => 'required|integer|min:0',
                'category_id' => 'nullable|exists:categories,id'
            ], [
                'name.required' => 'O nome do produto é obrigatório.',
                'name.string' => 'O nome do produto deve ser uma string válida.',
                'name.max' => 'O nome do produto não pode ter mais de :max caracteres.',
                
                'description.string' => 'A descrição deve ser uma string válida.',
                
                'price.required' => 'O preço do produto é obrigatório.',
                'price.numeric' => 'O preço do produto deve ser um número válido.',
                
                'mainImage.url' => 'A imagem principal deve ser uma URL válida.',
                
                'images.array' => 'As imagens devem ser fornecidas como um array.',
                'images.*.url' => 'Cada imagem deve ser uma URL válida.',
                
                'stock.required' => 'A quantidade em estoque é obrigatória.',
                'stock.integer' => 'A quantidade em estoque deve ser um número inteiro.',
                'stock.min' => 'A quantidade em estoque não pode ser menor que :min.',
                
                'category_id.exists' => 'A categoria selecionada não existe.',
            ]);
            
            // Criação do produto
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'mainImage' => $request->mainImage,
                'images' => $request->images ?? [], // Se não enviar, salva como array vazio
                'stock' => $request->stock,
                'category_id' => $request->category_id,
            ]);

            // Resposta de sucesso
            return response()->json([
                'status'=>true,
                'message' => 'Produto criado com sucesso!',
                'product' => $product
            ], 201);

        }catch(ValidationException $e){
            // Resposta em caso de erro de validação
            return response()->json([
                'status'=>false,
                'message' => 'Erro de validação.',
                'errors' => $e->errors()
            ], 422);
        }
    }

     /**
     * Exibe os detalhes de um produto específico.
     *
     * @param Product $product O objeto do produto a ser exibido.
     * @return JsonResponse
     *
     * Exemplo de requisição:
     * GET /api/products/{id}
     * 
     * Resposta:
     * {
     *   "status": true,
     *   "message": {
     *     "id": 1,
     *     "name": "Produto Exemplo",
     *     "description": "Este é um produto de exemplo.",
     *     "price": 199.99,
     *     "mainImage": "https://example.com/imagem-principal.jpg",
     *     "created_at": "2024-02-27T12:00:00.000000Z",
     *     "updated_at": "2024-02-27T12:00:00.000000Z"
     *   }
     * }
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $product,
        ], 200);
    }


    /**
    * Atualiza o recurso especificado no armazenamento.
    *
    * @param Request $request Os dados enviados na requisição.
    * @param Product $product O produto a ser atualizado.
    * @return JsonResponse Resposta em formato JSON com status e mensagem de sucesso ou erro.
    *
    * Exemplo de requisição:
    * PATCH /api/products/{id}
    * 
    * Corpo da requisição:
    * {
    *   "name": "Produto Atualizado",
    *   "description": "Descrição do produto atualizado.",
    *   "price": 150.00,
    *   "mainImage": "https://example.com/image.jpg",
    *   "images": ["https://example.com/image1.jpg", "https://example.com/image2.jpg"],
    *   "stock": 100,
    *   "category_id": 2
    * }
    *
    * Resposta de sucesso:
    * {
    *   "status": true,
    *   "message": "Produto atualizado com sucesso!",
    *   "product": {
    *     "id": 1,
    *     "name": "Produto Atualizado",
    *     "description": "Descrição do produto atualizado.",
    *     "price": 150.00,
    *     "mainImage": "https://example.com/image.jpg",
    *     "images": ["https://example.com/image1.jpg", "https://example.com/image2.jpg"],
    *     "stock": 100,
    *     "category_id": 2,
    *     "created_at": "2025-03-03T00:00:00",
    *     "updated_at": "2025-03-03T00:00:00"
    *   }
    * }
    *
    * Resposta de erro:
    * {
    *   "status": false,
    *   "message": "Erro de validação.",
    *   "errors": {
    *     "name": ["O nome do produto é obrigatório."],
    *     "price": ["O preço do produto deve ser um número válido."]
    *   }
    * }
    */
    public function update(Request $request, Product $product): JsonResponse
    {
        try {
            // Validação dos dados
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'mainImage' => 'nullable|url',
                'images' => 'nullable|array',
                'images.*' => 'url',
                'stock' => 'required|integer|min:0',
                'category_id' => 'nullable|exists:categories,id'
            ], [
                'name.required' => 'O nome do produto é obrigatório.',
                'name.string' => 'O nome do produto deve ser uma string válida.',
                'name.max' => 'O nome do produto não pode ter mais de :max caracteres.',
                'description.string' => 'A descrição deve ser uma string válida.',
                'price.required' => 'O preço do produto é obrigatório.',
                'price.numeric' => 'O preço do produto deve ser um número válido.',
                'mainImage.url' => 'A imagem principal deve ser uma URL válida.',
                'images.array' => 'As imagens devem ser fornecidas como um array.',
                'images.*.url' => 'Cada imagem deve ser uma URL válida.',
                'stock.required' => 'A quantidade em estoque é obrigatória.',
                'stock.integer' => 'A quantidade em estoque deve ser um número inteiro.',
                'stock.min' => 'A quantidade em estoque não pode ser menor que :min.',
                'category_id.exists' => 'A categoria selecionada não existe.',
            ]);
    
            // Atualização do produto
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'mainImage' => $request->mainImage,
                'images' => $request->images ?? [], // Se não enviar, salva como array vazio
                'stock' => $request->stock,
                'category_id' => $request->category_id,
            ]);
    
            // Resposta de sucesso
            return response()->json([
                'status' => true,
                'message' => 'Produto atualizado com sucesso!',
                'product' => $product
            ], 200);
    
        } catch (ValidationException $e) {
            // Resposta em caso de erro de validação
            return response()->json([
                'status' => false,
                'message' => 'Erro de validação.',
                'errors' => $e->errors()
            ], 422);
        }
    }
    

    /**
    * Remove o recurso especificado do armazenamento.
    *
    * @param Product $product O produto a ser deletado.
    * @return JsonResponse Resposta em formato JSON com status e mensagem de sucesso.
    *
    * Exemplo de requisição:
    * DELETE /api/products/{id}
    *
    * Resposta de sucesso:
    * {
    *   "status": true,
    *   "message": "Produto deletado!"
    * }
    */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'status'=>true,
            'message' => 'Produto deletado!'
        ], 200);
    }
}
