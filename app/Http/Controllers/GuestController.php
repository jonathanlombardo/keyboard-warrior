<?php

namespace App\Http\Controllers;

use App\Models\Lion;
use App\Models\Plot;
use Illuminate\Http\Request;

class GuestController extends Controller
{
  public function index()
  {

    $lions = [];

    for ($i = 0; $i < 10; $i++) {

      $randLion = Lion::randomLion();
      $randLion->user_id = 1;
      $randLion->save();

    }

    Plot::calcAllSinergy();
    Lion::calcAllMod();

    foreach (Lion::all() as $lion) {
      $lion = Lion::reMap($lion);
      $lions[] = $lion;
    }

    return response()->json($lions);

    // return view('guest.index');
  }
}
