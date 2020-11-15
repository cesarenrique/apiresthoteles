<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Precios extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'precio',
      'Pension_id',
      'TipoHabitacion_id',
      'Temporada_id',
  ];

  public function vender(){
      return $this->belongsTo(Pension_TipoHabitacion::class);
  }

  public function pertenece(){
      return $this->belongsTo(Temporada::class);
  }
}
