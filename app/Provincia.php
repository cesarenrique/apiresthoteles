<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'id',
      'nombre',
      'Pais_id',
  ];
}
