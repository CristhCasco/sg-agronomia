<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->integer('ci')->length(10)->nullable();
            $table->string('user', 255)->unique()->nullable();
            $table->string('password', 255);
            $table->string('email')->unique()->nullable();
            $table->string('phone', 255)->nullable();
            $table->text('address', 500)->nullable();
            $table->string('profile', 255)->nullable();
            $table->enum('status', ['ACTIVO', 'BLOQUEADO'])->default('ACTIVO')->nullable();
            $table->string('image', 255)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
