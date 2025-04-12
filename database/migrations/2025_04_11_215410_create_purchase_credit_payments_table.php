<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseCreditPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_credit_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('credit_id');
            $table->foreign('credit_id')->references('id')->on('purchase_credits')->onDelete('cascade');
            $table->decimal('amount_paid', 15, 2); // Monto del pago
            $table->date('payment_date'); // Fecha del pago
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que registra el pago
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
        Schema::dropIfExists('purchase_credit_payments');
    }
}
