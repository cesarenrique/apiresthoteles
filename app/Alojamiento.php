<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alojamiento extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'Pension_id',
      'TipoHabitacion_id',
      'Temporada_id',

  ];
}
