<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHabitacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habitacions', function (Blueprint $table) {
            $table->Integer('id')->unsigned();
            $table->string('numero');
            $table->Integer('Hotel_id')->unsigned();
            $table->Integer('TipoHabitacion_id')->unsigned();
            $table->primary(['Hotel_id','id']);
            $table->foreign('Hotel_id')->references('id')->on('hotels');
            $table->foreign('TipoHabitacion_id')->references('id')->on('tipo_habitacions');
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
        Schema::dropIfExists('habitacions');
    }
}
