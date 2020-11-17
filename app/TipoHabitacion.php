<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoHabitacion extends Model
{

  //basicos
  const HABITACION_SIMPLE="simple";
  const HABITACION_DOBLE="doble";
  const HABITACION_MATRIMONIAL="matrimonial";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'tipo',
      'hotel_id',
  ];
}
