<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('precio');
            $table->Integer('Pension_id')->unsigned();
            $table->Integer('TipoHabitacion_id')->unsigned();
            $table->Integer('Temporada_id')->unsigned();
            $table->Integer('Hotel_id')->unsigned();
            //$table->unique(['Pension_id','TipoHabitacion_id','Temporada_id','Hotels_id']);
            //$table->foreign('Pension_id','TipoHabitacion_id','Temporada_id')->references('Pension_id','TipoHabitacion_id','Temporada_id')->on('alojamientos');
            $table->foreign('Hotel_id')->references('id')->on('hotels');
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
        Schema::dropIfExists('precios');
    }
}
