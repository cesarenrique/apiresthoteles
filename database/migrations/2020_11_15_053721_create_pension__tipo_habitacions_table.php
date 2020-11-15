<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePensionTipoHabitacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pension__tipo_habitacions', function (Blueprint $table) {
            $table->Integer('Pension_id')->unsigned();
            $table->Integer('TipoHabitacion_id')->unsigned();

            $table->primary('Pension_id','TipoHabitacion_id');
            $table->foreign('Pension_id')->references('id')->on('pensions');
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
        Schema::dropIfExists('pension__tipo_habitacions');
    }
}
