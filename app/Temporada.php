<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Temporada extends Model
{
  //Basicas
  const TEMPORADA_BAJA="baja";
  const TEMPORADA_MEDIA="media";
  const TEMPORADA_ALTA="alta";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'tipo',
  ];
}
