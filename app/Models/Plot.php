<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plot extends Model
{
  use HasFactory;

  public function lions()
  {
    return $this->belongsToMany(Lion::class)->withPivot('supported');
  }
}