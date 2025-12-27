<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Cria o ID automático
            $table->string('name'); // Nome do produto
            $table->decimal('price', 10, 2); // Preço (10 digitos, 2 decimais)
            $table->string('image')->nullable(); // Caminho da imagem (pode ser nulo)
            $table->timestamps(); // Cria created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
