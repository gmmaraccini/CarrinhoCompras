<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product; // Importante: não esqueça de importar o Model

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Limpa a tabela antes de adicionar (opcional, mas bom para evitar duplicatas em testes)
        // Product::truncate();

        $produtos = [
            [
                'name' => 'Notebook Gamer',
                'price' => 4500.00,
                'image' => 'https://placehold.co/300x200?text=Notebook'
            ],
            [
                'name' => 'Mouse Sem Fio',
                'price' => 120.50,
                'image' => 'https://placehold.co/300x200?text=Mouse'
            ],
            [
                'name' => 'Monitor 27 Polegadas',
                'price' => 1200.00,
                'image' => 'https://placehold.co/300x200?text=Monitor'
            ],
            [
                'name' => 'Teclado Mecânico',
                'price' => 350.00,
                'image' => 'https://placehold.co/300x200?text=Teclado'
            ]
        ];

        foreach ($produtos as $produto) {
            Product::create($produto);
        }
    }
}
