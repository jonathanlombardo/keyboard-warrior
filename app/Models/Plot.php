<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Plot extends Model
{
  use HasFactory;

  // protected $appends = ['sinergy'];

  public function lions()
  {
    return $this->belongsToMany(Lion::class)->withPivot('supported');
  }

  public function users()
  {
    return $this->belongsToMany(User::class)->withPivot('sinergy');
  }

  public function calcSin()
  {
    //Retrieve user's Lion
    $userId = Auth::id();
    $userLions = Lion::where('user_id', $userId)->get();


    // Init plot sinergy
    $plotSin = 0;

    // Iter all user's lion
    foreach ($userLions as $lion) {
      // If lion has the plot in supported plots increment sinergy, otherwise decrement
      foreach ($lion->plots as $lionPlot) {
        if ($lionPlot->id == $this->id) {
          $plotSin = $lionPlot->pivot->supported ? $plotSin + 1 : $plotSin - 1;
        }
      }
    }


    // Update sinergy
    $this->users()->detach([Auth::id()]);
    $this->users()->attach([Auth::id()], ['sinergy' => $plotSin]);

  }

  static function calcAllSinergy()
  {
    $plots = Plot::all();

    foreach ($plots as $plot) {
      $plot->calcSin();
    }
  }

  public function getSinergyAttribute()
  {
    foreach (Auth::user()->plots as $plot) {
      if ($plot->id == $this->id)
        return $plot->pivot->sinergy;
    }

    return 0;

  }
}
