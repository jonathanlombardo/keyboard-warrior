<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  protected $appends = ['userSinergies'];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function lions()
  {
    return $this->hasMany(Lion::class);
  }
  public function plots()
  {
    return $this->belongsToMany(Plot::class)->withPivot('sinergy');
  }

  public function getUserSinergiesAttribute()
  {
    $lions = Lion::whereBelongsTo($this)->get();
    $plots = $this->plots;
    $userPlots = [];
    foreach ($plots as $plot) {
      $plotSin = $plot->pivot->sinergy;
      $sinPoints = 0;

      foreach ($lions as $lion) {
        foreach ($lion->plots as $lionPlot) {
          if ($lionPlot->id == $plot->id) {
            $sinPoints += $lionPlot->pivot->supported ? $plotSin : $plotSin * -1;
          }
        }
      }

      $userPlots[] = [
        'label' => $plot->name,
        'sinergy' => $plotSin,
        'sinergyPoints' => $sinPoints,
      ];
    }

    return $userPlots;
  }

  public function calcSin()
  {
    $userSinergies = $this->userSinergies;
    $totalSin = 0;
    foreach ($userSinergies as $sin) {
      $totalSin += $sin['sinergyPoints'];
    }
    $this->sinergy = $totalSin;
    $this->save;
  }
}
