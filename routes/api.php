<?php

use App\Http\Controllers\Api\DemandeEmploiController;
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
Route::apiResource('demandes-emploi', DemandeEmploiController::class);
Route::post('/demandes-emploi/{id}', [DemandeEmploiController::class, 'update']);//quand on a besoin de mettre à jour un objet ; la difference entre ajouter et modifier c'est l'id dans l'url
Route::delete('/demandes-emploi/{id}', [DemandeEmploiController::class, 'destroy']);//quand on a besoin de mettre à jour un objet ; la difference entre ajouter et modifier c'est l'id dans l'url


