<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time', function (Blueprint $table) {
            $table->increments('codigo_time');
            $table->string('nome', 35)->unique();

            $table->unsignedInteger('codigo_tecnico');
            $table->unsignedInteger('codigo_categoria');
            $table->unsignedInteger('codigo_divisao');

            $table->tinyInteger('desempenho_time');
            $table->tinyInteger('comprar_novo_jogador');
            $table->string('capa', 100);
            $table->timestamps();


            $table->foreign('codigo_tecnico')->references('codigo_tecnico')->on('tecnico')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('codigo_categoria')->references('codigo_categoria')->on('categoria')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('codigo_divisao')->references('codigo_divisao')->on('divisao')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time');
    }
}
