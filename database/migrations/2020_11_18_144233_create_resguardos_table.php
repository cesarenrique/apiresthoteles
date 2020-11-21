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
            $table->string('pagado');
            $table->string('precio');
            $table->string('Estado');
            $table->unique(['Fecha_id','Habitacion_id','Hotel_id']);
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
