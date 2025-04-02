<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtraExpensesTable extends Migration
{
    public function up()
    {
        Schema::create('extra_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index(); // Usuario que ingresa el gasto
            $table->decimal('amount', 10, 2); // Monto del gasto
            $table->text('description')->nullable(); // Descripción opcional
            $table->date('date'); // Fecha del gasto
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('extra_expenses');
    }
}

