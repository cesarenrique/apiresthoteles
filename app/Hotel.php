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
      'localidad',
      'provincia',
      'comunidad',
      'pais',
  ];

  public function resguardoMinimo(){
      return $this->hasOne(ResguardoHotel::class);
  }


}
