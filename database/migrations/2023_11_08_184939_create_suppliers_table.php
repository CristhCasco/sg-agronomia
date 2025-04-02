<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->enum('person', ['FISICA', 'JURIDICA'])->default('FISICA');
            $table->string('name', 255);
            $table->string('last_name', 255);
            $table->integer('ci')->length(10);
            $table->string('company', 255)->nullable();
            $table->string('ruc', 255)->nullable();
            $table->text('address', 500)->nullable();
            $table->string('phone', 255);
            $table->date('birthday')->nullable();
            $table->string('email', 255)->nullable();
            $table->string('image', 255)->nullable();
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
        Schema::dropIfExists('suppliers');
    }
}
