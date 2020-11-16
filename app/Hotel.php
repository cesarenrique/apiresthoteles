<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'nombre',
      'NIF',
      'Localidad_id',
      'Provincia_id',
      'Pais_id',
  ];

  public function resguardoMinimo(){
      return $this->hasOne(ResguardoHotel::class);
  }


}
