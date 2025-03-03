<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FavoriteProductController extends Controller
{
    /**
     * Lista os produtos favoritados pelo usuário.
     * Realiza uma verificação de autenticação e retorna uma lista paginada.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = Auth::user(); // Obtém o usuário autenticado

        // Se o usuário não estiver autenticado, retorna erro
        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado'], 401);
        }
    
        // Obtém os produtos favoritados pelo usuário com a categoria associada e paginados (20 por vez)
        $favoriteProducts = $user->favoriteProducts()->with('category')->paginate(20);
    
        // Verifica se o usuário ainda não favoritou nenhum produto
        if ($favoriteProducts->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Você ainda não favoritou nenhum produto.' // Mensagem de resposta caso não haja favoritos
            ], 200);
        }
    
        // Se o usuário tem produtos favoritados, retorna a lista com status de sucesso
        return response()->json([
            'status' => true,
            'favorites' => $favoriteProducts
        ], 200);
    }
    
    /**
     * Adiciona um produto aos favoritos do usuário.
     * Utiliza o método syncWithoutDetaching para evitar duplicação de entradas.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function store(Product $product)
    {
        $user = Auth::user(); // Obtém o usuário autenticado
        
        // Adiciona o produto aos favoritos, sem remover os outros produtos favoritos
        $user->favoriteProducts()->syncWithoutDetaching([$product->id]);
    
        return response()->json([
            'status' => true,
            'message' => 'Produto favoritado com sucesso!' // Retorna a mensagem de sucesso
        ], 200);
    }
    
    /**
     * Remove um produto dos favoritos do usuário.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product)
    {
        $user = Auth::user(); // Obtém o usuário autenticado
    
        // Remove o produto dos favoritos
        $user->favoriteProducts()->detach($product->id);
    
        return response()->json([
            'status' => true,
            'message' => 'Produto removido dos favoritos!' // Retorna a mensagem de sucesso
        ], 200);
    }
    
}
