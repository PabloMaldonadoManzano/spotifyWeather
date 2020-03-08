<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusquedasResultadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('busquedas_resultados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('busqueda_id');
            $table->string('city');
            $table->string('lat');
            $table->string('lon');
            $table->string('temp');
            $table->string('timezone');
            $table->string('type');
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
        Schema::dropIfExists('busquedas_resultados');
    }
}
