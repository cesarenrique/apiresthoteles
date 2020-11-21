<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Reserva;

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
            $table->increments('id');
            $table->string('reservado')->default(Reserva::LIBRE);
            $table->Integer('Fecha_id')->unsigned();
            $table->Integer('Pension_id')->unsigned();
            $table->Integer('TipoHabitacion_id')->unsigned();
            $table->Integer('Habitacion_id')->unsigned();
            $table->Integer('Hotel_id')->unsigned();

            $table->Integer('Temporada_id')->unsigned();

            $table->unique(['Fecha_id','Habitacion_id','Hotel_id']);
            //Controlar con programacion
            //$table->unique(['Fecha_id','Pension_id','TipoHabitacion_id','Temporada_id','Hotel_id']);
            //$table->foreign('Pension_id','TipoHabitacion_id','Temporada_id','Hotel_id')->references('Pension_id','TipoHabitacion_id','Temporada_id','Hotels_id')->on('precios');
            $table->foreign('Fecha_id')->references('id')->on('fechas');
            $table->foreign('Temporada_id')->references('id')->on('temporadas');
            $table->foreign('Hotel_id','Habitacion_id')->references('Hotel_id','id')->on('habitacions');

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
