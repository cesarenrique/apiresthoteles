<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'numero',
      'hotel_id',
      'TipoHabitacion_id',
  ];
}
