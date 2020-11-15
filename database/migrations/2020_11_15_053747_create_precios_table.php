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
            $table->string('precio');
            $table->Integer('Pension_id')->unsigned();
            $table->Integer('TipoHabitacion_id')->unsigned();
            $table->Integer('Temporada_id')->unsigned();
            $table->primary('Pension_id','TipoHabitacion_id');
            $table->foreign('Pension_id','TipoHabitacion_id')->references('Pension_id','TipoHabitacion_id')->on('pension__tipo_habitacions');
            $table->foreign('Temporada_id')->references('id')->on('temporadas');
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
