<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lion;
use App\Models\Plot;
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

    Lion::whereBelongsTo(Auth::user())->delete();

    $lions = [];
    $n = config('lion.initGame.lions');

    for ($i = 0; $i < $n; $i++) {

      $randLion = Lion::randomLion();
      $randLion->user_id = Auth::id();
      $randLion->save();

      $randLion = Lion::reMap($randLion);
      $lions[] = $randLion;

    }

    // Plot::calcAllSinergy();
    // Lion::calcAllMod();

    // foreach (Lion::whereBelongsTo(Auth::user())->get() as $lion) {
    //   $lion = Lion::reMap($lion);
    //   $lions[] = $lion;
    // }

    return response()->json($lions);
  }



  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  // public function destroy($id)
  // {
  //   //
  // }
}
