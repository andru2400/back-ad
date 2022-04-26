<?php

use App\Http\Controllers\Api\UserController;
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

Route::get('random', function () {
    $numero = 5000;
    // $numero = rand(5, 15);
    return response()->json(['status' => 'ok', 'numero' => $numero , 'proyecto' => env('CURRENT_APPLICATION_KEY')], 200);
});

// API
Route::post('register', [UserController::class, 'register']);
Route::post('login'   , [UserController::class, 'login']);

// Proteccion Sanctum
Route::middleware('auth:sanctum')->group(function () {

    Route::get('user-profile',[UserController::class, 'userProfile']);
    Route::get('logout'      ,[UserController::class, 'logout']);

});
