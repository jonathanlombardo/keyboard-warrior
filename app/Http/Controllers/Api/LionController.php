<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lion;
use App\Models\Plot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function initLions(Request $request)
  {
    $user = Auth::user();
    Lion::whereBelongsTo($user)->delete();
    $user->sinergy = 0;
    $user->save();

    $lions = [];
    $n = config('lion.initGame.lions');

    for ($i = 0; $i < $n; $i++) {

      $randLion = Lion::randomLion();
      $randLion->user_id = Auth::id();
      $randLion->save();

      $randLion = Lion::reMap($randLion);
      $lions[] = $randLion;

    }

    return response()->json(['lions' => $lions, 'nLions' => config('lion.initGame.lionsToChoose')]);
  }

  public function reCalcAll(Request $request)
  {
    $user = Auth::user();
    Plot::calcAllSinergy();
    Lion::calcAllMod();
    $userSinergy = $user->userSinergies;
    $user->calcSin();

    $global = [
      'sinergy' => $user->sinergy,
      'belief' => $user->belief
    ];

    $allLions = Lion::whereBelongsTo($user)->get();
    $lions = [];

    foreach ($allLions as $lion) {
      $lions[] = Lion::reMap($lion);
    }

    return response()->json(['lions' => $lions, 'sinergies' => $userSinergy, 'global' => $global]);
  }



  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroyUnchoosed(Request $request)
  {
    $ids = $request->ids;

    if (count($ids) < config('lion.initGame.lionsToChoose')) {
      return response()->json('fail');
    }

    $ids = array_filter($ids, fn($key) => $key < config('lion.initGame.lionsToChoose'), ARRAY_FILTER_USE_KEY);
    Lion::whereNotIn('id', $ids)->delete();
    return response()->json('success');
  }
}
