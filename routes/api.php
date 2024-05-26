<?php

use App\Http\Controllers\Api\LionController;
use App\Http\Controllers\Api\UserController;
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

Route::post('/auth/register', [UserController::class, 'createUser']);
Route::post('/auth/login', [UserController::class, 'loginUser']);

Route::middleware('auth:sanctum')->name('api.')->group(function () {
  Route::get('/lions', [LionController::class, 'initLions'])->name('api.lions.initLions');
  Route::post('/destroy', [LionController::class, 'destroyUnchoosed'])->name('lions.destroyUnchoosed');
  Route::get('/recalc', [LionController::class, 'reCalcAll'])->name('lions.reCalcAll');
});


