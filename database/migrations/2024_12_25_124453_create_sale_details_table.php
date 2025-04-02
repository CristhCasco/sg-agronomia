<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
        public function up()
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 10, 2)->nullable(); // Precio unitario o por peso
            $table->decimal('quantity', 10, 3)->nullable(); // Cantidad (unidades o peso)
            $table->decimal('sub_total', 10, 2)->default(0); // Subtotal de la línea de detalle

            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_details');
    }
}
