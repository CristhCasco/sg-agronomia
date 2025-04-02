<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('items', 15, 3);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('cash', 15, 2)->default(0);
            $table->decimal('change', 15, 2)->default(0);
            $table->enum('status', ['PAGADO', 'PENDIENTE', 'CANCELADO'])->default('PAGADO');
            $table->enum('payment_type', ['CONTADO', 'CREDITO'])->default('CONTADO');
            $table->enum('payment_method', ['EFECTIVO', 'TDD', 'TDC', 'TRANS', 'TIGO', 'CHEQUE', 'OTROS'])->default('EFECTIVO');
            $table->decimal('discount', 15, 2)->nullable();
            $table->decimal('discount_total', 15, 2)->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('sales');
    }
}
