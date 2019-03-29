<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->integer('id')->unsigned();
            $table->string('username', 30)->unique();
            $table->string('password', 255);
            $table->boolean('condicion')->default(1);
            $table->integer('id_rol')->unsigned();

            //Foreign Key
            $table->foreign('id')->references('id')->on('personas')->onDelete('cascade');
            $table->foreign('id_rol')->references('id')->on('roles');

            $table->rememberToken();
            //$table->timestamps();
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
