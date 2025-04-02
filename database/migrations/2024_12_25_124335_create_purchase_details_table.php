<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 10, 2); // Cambiado a decimal para manejar precios con centavos
            $table->decimal('quantity', 10, 3); // Cambiado a decimal para manejar cantidades no enteras
            $table->decimal('total', 10, 2); // Cambiado a decimal para manejar totales con centavos
            
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
    
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
        Schema::dropIfExists('purchase_details');
    }
}