<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'main_image', 'images', 'category_id', 'stock', 'price'];

    protected $casts = [
        'images' => 'array', // Para salvar e recuperar um array de imagens corretamente
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
