<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalidadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localidads', function (Blueprint $table) {
            $table->Integer('id')->unsigned();
            $table->string('nombre');
            $table->Integer('Pais_id')->unsigned();
            $table->Integer('Provincia_id')->unsigned();
            $table->primary(['Provincia_id','id']);
            $table->foreign('Pais_id','Provincia_id')->references('Pais_id','id')->on('provincias');
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
        Schema::dropIfExists('localidads');
    }
}
