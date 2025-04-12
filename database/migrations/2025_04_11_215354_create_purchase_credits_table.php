<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->decimal('total_credit', 15, 2); // Monto total del crédito
            $table->decimal('amount_paid', 15, 2)->default(0); // Total abonado
            $table->decimal('remaining_balance', 15, 2); // Saldo pendiente
            $table->enum('status', ['PENDIENTE', 'PAGADO'])->default('PENDIENTE'); // Estado del crédito
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
        Schema::dropIfExists('purchase_credits');
    }
}
