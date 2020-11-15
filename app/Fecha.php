<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fecha extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'fecha',
  ];
}
