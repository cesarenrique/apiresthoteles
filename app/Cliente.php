<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'nombre',
      'NIF',
      'email',
      'telefono',
  ];

  public function tiene(){
      return $this->hasMany(Tarjeta::class);
  }



}
