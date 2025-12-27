<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Chama o seeder específico de produtos
        $this->call([
            ProductSeeder::class,
        ]);
    }
}
