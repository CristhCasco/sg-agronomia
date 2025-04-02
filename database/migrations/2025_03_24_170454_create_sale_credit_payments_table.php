<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleCreditPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_credit_payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('credit_id');
            $table->foreign('credit_id')->references('id')->on('sale_credits')->onDelete('cascade'); // Crédito al que se le realiza el pago
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
        Schema::dropIfExists('sale_credit_payments');
    }
}
