<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Temporada;

class Fecha extends Model
{
  const INICIAL='2019-01-01';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'fecha',
  ];

  public function pertenece(){
      return $this->belongsTo(Temporada::class);
  }
}
