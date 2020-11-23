<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('NIF');
            $table->string('nombre');
            $table->Integer('Provincia_id')->unsigned();
            $table->Integer('Localidad_id')->unsigned();
            $table->Integer('Pais_id')->unsigned();
            $table->foreign(['Pais_id','Provincia_id','Localidad_id'])->references(['Pais_id','Provincia_id','id'])->on('localidads');
            $table->foreign(['Pais_id','Provincia_id'])->references(['Pais_id','id'])->on('provincias');
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
        Schema::dropIfExists('hotels');
    }
}
