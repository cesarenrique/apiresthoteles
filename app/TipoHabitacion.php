<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoHabitacion extends Model
{
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
