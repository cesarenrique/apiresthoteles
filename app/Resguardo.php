<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resguardo extends Model
{
  const RESERVA_ACEPTADA="Aceptado";
  const RESERVA_FALLIDA="fallo";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'pagado',
  ];

  public function pertenecePago(){
      return $this->belongsTo(Reserva::class);
  }
}
