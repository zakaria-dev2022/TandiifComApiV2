<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CommandeController;
use App\Http\Controllers\Api\CommentaireController;
use App\Http\Controllers\Api\DemandeEmploiController;
use App\Http\Controllers\Api\EmploieController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\ServiceController;
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

// ************************************************************************************************************************
Route::apiResource('demandes-emploi', DemandeEmploiController::class);
Route::post('/demandes-emploi/{id}', [DemandeEmploiController::class, 'update']);//quand on a besoin de mettre à jour un objet ; la difference entre ajouter et modifier c'est l'id dans l'url
Route::post('/demandes-emploi/{id}/accepte', [DemandeEmploiController::class, 'accepterDemande']);//acceptation de demande d'emploie
// ************************************************************************************************************************
Route::apiResource('emploie', EmploieController::class);
Route::post('/emploie/{id}', [EmploieController::class, 'update']);//quand on a besoin de mettre à jour un objet ; la difference entre ajouter et modifier c'est l'id dans l'url
// ************************************************************************************************************************
Route::apiResource('client',ClientController::class);
Route::post('/client/{id}', [ClientController::class, 'update']);//quand on a besoin de mettre à jour un objet ; la difference entre ajouter et modifier c'est l'id dans l'url
// ************************************************************************************************************************
Route::apiResource('service',ServiceController::class);
Route::post('/service/{id}', [ServiceController::class, 'update']);//quand on a besoin de mettre à jour un objet ; la difference entre ajouter et modifier c'est l'id dans l'url
// ************************************************************************************************************************
Route::apiResource('reservation',ReservationController::class);
Route::post('/reservation/{id}', [ReservationController::class, 'update']);//quand on a besoin de mettre à jour un objet ; la difference entre ajouter et modifier c'est l'id dans l'url
// ************************************************************************************************************************
Route::apiResource('commentaire',CommentaireController::class);
Route::post('/commentaire/{id}', [CommentaireController::class, 'update']);//quand on a besoin de mettre à jour un objet ; la difference entre ajouter et modifier c'est l'id dans l'url
// ************************************************************************************************************************
Route::apiResource('article',ArticleController::class);
Route::post('/article/{id}', [ArticleController::class, 'update']);//quand on a besoin de mettre à jour un objet ; la difference entre ajouter et modifier c'est l'id dans l'url
// ************************************************************************************************************************
Route::apiResource('commande',CommandeController::class);
Route::post('/commande/{id}', [CommandeController::class, 'update']);//quand on a besoin de mettre à jour un objet ; la difference entre ajouter et modifier c'est l'id dans l'url
// ************************************************************************************************************************

