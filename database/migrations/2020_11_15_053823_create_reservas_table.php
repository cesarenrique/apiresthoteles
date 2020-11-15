<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            //$table->increments('id');
            $table->Integer('Fecha_id')->unsigned();
            $table->Integer('Pension_id')->unsigned();
            $table->Integer('TipoHabitacion_id_reservas')->unsigned();
            $table->Integer('Habitacion_id')->unsigned();
            $table->Integer('Hotel_id')->unsigned();
            $table->Integer('Cliente_id')->unsigned();
            $table->primary(['Fecha_id','Habitacion_id','Hotel_id']);
            $table->unique(['Fecha_id','Pension_id','TipoHabitacion_id_reservas']);
            $table->foreign('Pension_id','TipoHabitacion_id_reservas')->references('Pension_id','TipoHabitacion_id')->on('precios');
            $table->foreign('Fecha_id')->references('id')->on('fechas');
            $table->foreign('Hotel_id','Habitacion_id')->references('Hotel_id','id')->on('habitacions');
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
        Schema::dropIfExists('reservas');
    }
}
