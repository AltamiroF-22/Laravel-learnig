<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Roupas'],
            ['name' => 'Acessórios'],
            ['name' => 'Eletrônicos'],
            ['name' => 'Decoração'],
            ['name' => 'Livros'],
            ['name' => 'Calçados'],
            ['name' => 'Esportes'],
            ['name' => 'Games'],
            ['name' => 'Beleza e Saúde'],
            ['name' => 'Brinquedos']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
