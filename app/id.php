<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Id extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'nombre',
      'posicion',
      'ides',
  ];
}
