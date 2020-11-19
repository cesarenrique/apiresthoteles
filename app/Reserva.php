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
      'Habitacion_id',
      'Hotel_id',
      'Cliente_id',
      'Fecha_id',

  ];
/*
  public function reservar_relacion_A(){
      return $this->hasMany(Fecha::class);
  }

  public function reservar_relacion_B(){
      return $this->hasMany(Precios::class);
  }

  public function reservar_relacion_C(){
      return $this->hasMany(Habitacion::class);
  }

  public function reservar_relacion_D(){
      return $this->belongsTo(Cliente::class);
  }
*/
}
