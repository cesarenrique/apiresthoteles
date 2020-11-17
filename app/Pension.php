<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pension extends Model
{
  //basicos
  const SOLO_ALOJAMIENTO="solo alojamiento";
  const PENSION_DESAYUNO="solo desayuno";
  const PENSION_COMPLETA="desayuno y comida";
  const PENSION_COMPLETA_CENA="desayuno, comida y cena";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'tipo',

  ];

  public function tiene(){
      return $this->hasMany(Hotel::class);
  }
}
