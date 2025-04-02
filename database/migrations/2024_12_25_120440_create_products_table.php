<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
        public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); // Obligatorio
            $table->string('barcode', 255)->unique(); // Obligatorio
            $table->string('brand', 255)->nullable(); // Opcional
            $table->string('model', 255)->nullable(); // Opcional
            $table->string('size', 255)->nullable(); // Opcional
            $table->string('color', 255)->nullable(); // Opcional
            $table->text('description')->nullable(); // Opcional
            $table->boolean('is_weighted')->default(false); // Si el producto se vende por peso
            $table->decimal('cost', 10, 2)->nullable(); // Opcional, hasta dos decimales
            $table->decimal('price', 10, 2)->nullable(); // Opcional, precio por unidad
            $table->decimal('price_per_kg', 10, 2)->nullable(); // Opcional, precio por peso
            $table->decimal('stock', 10, 3)->default(0); // Obligatorio, manejo preciso hasta tres decimales
            $table->decimal('min_stock', 10, 3)->nullable(); // Opcional, manejo preciso hasta tres decimales
            $table->string('image', 255)->nullable(); // Opcional

            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Obligatorio, con relación
            
            $table->timestamps(); // Fecha de creación y actualización
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
