<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->decimal('items', 10, 3); // Cambiado a decimal para manejar cantidades no enteras
            $table->decimal('sub_total', 10, 2)->default(0); // Cambiado a decimal
            $table->decimal('total', 10, 2)->default(0); // Cambiado a decimal
            $table->decimal('cash', 10, 2); // Cambiado a decimal
            $table->decimal('change', 10, 2); // Cambiado a decimal
            $table->enum('status', ['PAGADO', 'PENDIENTE', 'CANCELADO'])->default('PAGADO');
            $table->enum('payment_type', ['CONTADO', 'CREDITO'])->default('CONTADO');
            $table->enum('payment_method', ['EFECTIVO', 'TARJETA_CREDITO', 'TARJETA_DEBITO', 'TRANSFERENCIA', 'TIGO_MONEY', 'CHEQUE', 'OTRO'])->default('EFECTIVO');
            $table->decimal('discount', 10, 2)->nullable(); // Cambiado a decimal
            $table->decimal('discount_total', 10, 2)->nullable(); // Cambiado a decimal
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('purchases');
    }
}