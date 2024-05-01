<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lion extends Model
{
  use HasFactory;


  public function plots()
  {
    return $this->belongsToMany(Plot::class)->withPivot('supported');
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  // Calc lion modifier and save it on DB
  public function calcMod()
  {
    $mod = 0;
    foreach ($this->plots as $plot) {
      $sin = $plot->sinergy;
      $sin = $plot->pivot->supported ? $sin : $sin * -1;
      $mod += $sin;
    }
    $this->modifier = $mod;
    $this->save();
  }

  // Generate a random Lion
  static function randomLion()
  {

    // generate a lion
    $lion = new Lion();
    $lion->name = "new Lion";
    $lion->modifier = 0;
    $lion->save();

    // retrieve all plots ids    
    $plotIds = Plot::all()->pluck('id')->toArray();

    //< retrieve configuration of ranges for lion's plots
    $plotConfig = config('lion')["plotConfig"];
    $minSupPlot = $plotConfig["minSupported"];
    $minUnsupPlot = $plotConfig["minUnsupported"];
    $maxSupPlot = $plotConfig["maxSupported"];
    $maxUnsupPlot = $plotConfig["maxUnsupported"];
    $minPlot = ($minSupPlot + $minUnsupPlot) > count($plotIds) ? count($plotIds) : ($minSupPlot + $minUnsupPlot);
    $maxPlot = ($maxSupPlot + $maxUnsupPlot) > count($plotIds) ? count($plotIds) : ($maxSupPlot + $maxUnsupPlot);
    //>

    $randNumberPlots = rand($minPlot, $maxPlot);
    $supportedPlot = [];
    $unsupportedPlot = [];

    if ($randNumberPlots > 0) {

      // Assign random plot to supported or unsupported lion's plots
      $randKeys = array_rand($plotIds, $randNumberPlots);
      shuffle($randKeys);

      foreach ($randKeys as $randKey) {

        // if plot supported is not enough add to supported
        if (count($supportedPlot) < $minSupPlot) {
          $supportedPlot[] = $plotIds[$randKey];

          // if plot unsupported is not enough add to unsupported
        } else if ((count($unsupportedPlot) < $minUnsupPlot)) {
          $unsupportedPlot[] = $plotIds[$randKey];

          // add to supported randomly if supported is not full, otherwise add to unsupported
        } else if ((rand(0, 1) && count($supportedPlot) < $maxSupPlot) || count($unsupportedPlot) >= $maxUnsupPlot) {
          $supportedPlot[] = $plotIds[$randKey];
        } else {
          $unsupportedPlot[] = $plotIds[$randKey];
        }
      }

      // attach plots to lion
      $lion->plots()->attach($supportedPlot, ['supported' => true]);
      $lion->plots()->attach($unsupportedPlot, ['supported' => false]);
    }


    return $lion;
  }

  // Remap a lion in order to return a clean json
  static function reMap($lion)
  {
    $supportedPlots = [];
    $unsupportedPlots = [];

    foreach ($lion->plots as $plot) {
      $mappedPlot = [
        "id" => $plot->id,
        "label" => $plot->label,
        "color" => $plot->color,
        "sinergy" => $plot->sinergy,
      ];
      if ($plot->pivot->supported) {
        $supportedPlots[] = $mappedPlot;
      } else {
        $unsupportedPlots[] = $mappedPlot;
      }
    }


    $lion = [
      "id" => $lion->id,
      "name" => $lion->name,
      "modifier" => $lion->modifier,
      "supportedPlots" => $supportedPlots,
      "unsupportedPlots" => $unsupportedPlots,
    ];

    return $lion;
  }
}