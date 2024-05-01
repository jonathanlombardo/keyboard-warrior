<?php

use App\Models\Lion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::get('/', function () {

  $lions = [];

  for ($i = 0; $i < 100; $i++) {

    $lion = Lion::randomLion();
    $lion->calcMod();
    $lion = Lion::reMap($lion);

    $lions[] = $lion;
  }
  return response()->json($lions);

});
