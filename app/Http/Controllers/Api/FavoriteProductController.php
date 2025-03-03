<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class FavoriteProductController extends Controller
{
    public function store(Product $product)
    {
        $user = Auth::user();
        
        // Adiciona aos favoritos (evita duplicação automaticamente)
        $user->favoriteProducts()->syncWithoutDetaching([$product->id]);
    
        return response()->json([
            'message' => 'Produto favoritado com sucesso!'
        ], 200);
    }
    
    public function destroy(Product $product)
    {
        $user = Auth::user();
    
        // Remove dos favoritos
        $user->favoriteProducts()->detach($product->id);
    
        return response()->json([
            'message' => 'Produto removido dos favoritos!'
        ], 200);
    }
    
}
