<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tarjeta extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'numero',
      'Cliente_id',
  ];

  public function pertenece(){
      return $this->belongsTo(Cliente::class);
  }
}
