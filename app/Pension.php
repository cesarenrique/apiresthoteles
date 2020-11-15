<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pension extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'tipo',
      'hotel_id',
  ];

  public function tiene(){
      return $this->belongsTo(Hotel::class);
  }
}
