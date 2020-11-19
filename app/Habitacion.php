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
      'id',
      'numero',
      'Hotel_id',
      'TipoHabitacion_id',
  ];

  public function pertenece(){
      return $this->belongsTo(Hotel::class);
  }

  public function esdetipo(){
      return $this->belongsTo(TipoHabitacion::class);
  }
}
