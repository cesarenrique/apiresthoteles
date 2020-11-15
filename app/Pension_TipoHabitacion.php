<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pension_TipoHabitacion extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'Pension_id',
      'TipoHabitacion_id',
  ];

  public function opcion_relacion_A(){
      return $this->belongsTo(Pension::class);
  }

  public function opcion_relacion_B(){
      return $this->belongsTo(TipoHabitacion::class);
  }

  public function opcion_relacion_C(){
      return $this->belongsTo(Temporada::class);
  }
}
