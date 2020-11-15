<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
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
      'Habitacion_id',
      'Cliente_id'
      'Fecha_id',
  ];
}
