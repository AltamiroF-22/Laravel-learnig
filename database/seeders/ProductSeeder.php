<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $products = [
            [
                'name' => 'Camiseta DevOps',
                'description' => 'Camiseta preta com estampa DevOps.',
                'main_image' => 'https://via.placeholder.com/300x300.png?text=Camiseta+DevOps',
                'images' => json_encode([
                    'https://via.placeholder.com/300x300.png?text=DevOps1',
                    'https://via.placeholder.com/300x300.png?text=DevOps2'
                ]),
                'category_id' => 1, // Supondo que a categoria 1 já exista
                'stock' => 100,
                'price' => 79.90
            ],
            [
                'name' => 'Caneca JavaScript',
                'description' => 'Caneca branca com logo JavaScript.',
                'main_image' => 'https://via.placeholder.com/300x300.png?text=Caneca+JS',
                'images' => json_encode([
                    'https://via.placeholder.com/300x300.png?text=Caneca1',
                    'https://via.placeholder.com/300x300.png?text=Caneca2'
                ]),
                'category_id' => 2,
                'stock' => 50,
                'price' => 39.90
            ],
            [
                'name' => 'Poster React',
                'description' => 'Poster minimalista do React.',
                'main_image' => 'https://via.placeholder.com/300x300.png?text=Poster+React',
                'images' => json_encode([
                    'https://via.placeholder.com/300x300.png?text=Poster1',
                    'https://via.placeholder.com/300x300.png?text=Poster2'
                ]),
                'category_id' => 3,
                'stock' => 30,
                'price' => 29.90
            ]
        ];

        // Gerando mais 50 produtos aleatórios
        for ($i = 0; $i < 50; $i++) {
            $products[] = [
                'name' => $faker->word . ' ' . $faker->word,
                'description' => $faker->sentence,
                'main_image' => 'https://via.placeholder.com/300x300.png?text=Produto+' . ($i + 1),
                'images' => json_encode([
                    'https://via.placeholder.com/300x300.png?text=Imagem1',
                    'https://via.placeholder.com/300x300.png?text=Imagem2'
                ]),
                'category_id' => rand(1, 3),
                'stock' => rand(10, 200),
                'price' => $faker->randomFloat(2, 10, 500)
            ];
        }

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
