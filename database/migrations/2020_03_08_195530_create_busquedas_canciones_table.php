<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusquedasCancionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('busquedas_canciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('busqueda_id');
            $table->string('track_id');
            $table->string('track');
            $table->string('artisat');
            $table->timestamps();
            $table->foreign('busqueda_id')->references('id')->on('busquedas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('busquedas_canciones');
    }
}
