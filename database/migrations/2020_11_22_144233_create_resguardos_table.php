<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResguardosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resguardos', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('Fecha_id')->unsigned();
            $table->Integer('Habitacion_id')->unsigned();
            $table->Integer('Hotel_id')->unsigned();
            $table->Integer('Alojamiento_id')->unsigned();
            $table->double('pagado');
            $table->double('precio');
            $table->string('Estado');
            $table->Integer('Cliente_id')->unsigned();
            //Controlar con programacion
            //$table->unique(['Fecha_id','Habitacion_id','Hotel_id','Alojamiento_id','Cliente_id']);
            $table->foreign('Cliente_id')->references('id')->on('clientes');
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
        Schema::dropIfExists('resguardos');
    }
}
