<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemporadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporadas', function (Blueprint $table) {

            $table->Integer('id')->unsigned();
            $table->string('tipo');
            $table->Integer('dia_desde');
            $table->Integer('mes_desde');
            $table->Integer('dia_hasta');
            $table->Integer('mes_hasta');
            $table->Integer('mismo');
            $table->Integer('Hotel_id')->unsigned();
            $table->primary(['Hotel_id','id']);
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
        Schema::dropIfExists('temporadas');
    }
}
