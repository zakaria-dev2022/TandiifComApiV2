<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AffectationReservation;
use App\Models\Reservation;
use Illuminate\Http\Request;

class AffectationReservationController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      try {
         $affectations = AffectationReservation::with([
            'reservation.client',
            'reservation.service',
            'employe',
            'vehicule'
         ])
            ->where('isDeleted', false)
            ->orderBy('date_affectation', 'desc')
            ->get();

         // Mapper les données pour format personnalisé
         $formatted = $affectations->map(function ($aff) {
            return [
               'affectation_id' => $aff->id,
               'reservation_id' => $aff->reservation_id,
               'vehicule_id' => $aff->vehicule_id,
               'employee_id' => $aff->emploie_id,
               'client_nom' => $aff->reservation->client->nom_complet,
               'client_tel' => $aff->reservation->client->tel,
               'client_email' => $aff->reservation->client->email,
               'service_nom' => $aff->reservation->service->nom,
               'date_reservation' => $aff->reservation->date_reservation,
               'heure' => $aff->reservation->heure,
               'adresse' => $aff->reservation->adresse,
               'employee_nom' => $aff->employe->nom_complet,
               'employee_matricule' => $aff->employe->matricule,
               'vehicule_marque' => $aff->vehicule->marque,
               'vehicule_matricule' => $aff->vehicule->matricule,
               'status' => $aff->status,
               'commentaire_employee' => $aff->commentaire_emploie,
            ];
         });

         return response()->json($formatted, 200);
      } catch (\Exception $e) {
         return response()->json(['error' => 'Erreur lors de la recuperation des reservations.'], 400);
      }
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      try {

         try {
            $request->validate([
               'emploie_id' => 'required|exists:emploies,id',
               'reservation_id' => 'required|exists:reservations,id',
               'vehicule_id' => 'required|exists:vehicules,id',
               'date_affectation' => 'required|date',
               'status' => 'required|string|max:55',
               'commentaire_emploie' => 'nullable|string|max:1000',
            ]);
         } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['erreur' => 'l\'un des conditions non valide ' + $th], 408);
         }


         try {
            $affectationReservation = AffectationReservation::create([
               'emploie_id' => $request->emploie_id,
               'reservation_id' => $request->reservation_id,
               'vehicule_id' => $request->vehicule_id,
               'date_affectation' => $request->date_affectation,
               'status' => $request->status,
               'commentaire_emploie' => $request->commentaire_emploie

            ]);

            // 2. Mise à jour du statut de la réservation liée
            $reservation = Reservation::find($request->reservation_id);
            if ($reservation) {
               $reservation->status = 'Affecter';
               $reservation->save();
            }
            return response()->json(['message' => $affectationReservation], 201);
         } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['erreur' => 'problemedans la creation de affectationReservation '], 408);
         }
      } catch (\Throwable $th) {
         //throw $th;
         return response()->json(['error' => 'An error store affectationReservation.', $th], 500);
      }
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
      //    try {
      //    $affectationReservation = AffectationReservation::where('IsDeleted', false)->find($id);
      //    if ($affectationReservation) {
      //       return response()->json(['message' => $affectationReservation], 200);
      //    } else {
      //       return response()->json(['message' => 'AffectationReservation not found.'], 404);
      //    }
      // } catch (\Throwable $th) {
      //    //throw $th;
      //    return response()->json(['error' => 'An error show affectationReservation.', $th], 500);
      // }
      $affectation = AffectationReservation::with([
         'reservation.client',
         'reservation.service',
         'employe',
         'vehicule'
      ])->findOrFail($id);

      return response()->json([
         'affectation_id' => $affectation->id,
         'client_nom_complet' => $affectation->reservation->client->nom_complet,
         'reservation_id' => $affectation->reservation_id,
         'service' => $affectation->reservation->service->nom,
         'date_reservation' => $affectation->reservation->date_reservation,
         'heure' => $affectation->reservation->heure,
         'employe_nom' => $affectation->employe->nom_complet,
         'employe_matricule' => $affectation->employe->matricule,
         'vehicule_matricule' => $affectation->vehicule->matricule,
         'status' => $affectation->status
      ]);
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {
      try {
         try {
            $request->validate([
               'emploie_id' => 'required|exists:emploie,id',
               'reservation_id' => 'required|exists:reservation,id',
               'vehicule_id' => 'required|exists:vehicule,id',
               'date_affectation' => 'required|date',
               'status' => 'required|string|max:55',
               'commentaire_emploie' => 'nullable|string|max:1000',
            ]);
         } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
         }

         $affectationReservation = AffectationReservation::where('isDeleted', false)->find($id);
         if ($affectationReservation) {
            try {
               $affectationReservation->update([
                  'emploie_id' => $request->emploie_id,
                  'reservation_id' => $request->reservation_id,
                  'vehicule_id' => $request->vehicule_id,
                  'date_affectation' => $request->date_affectation,
                  'status' => $request->status,
                  'commentaire_emploie' => $request->commentaire_emploie
               ]);
               return response()->json(['message' => 'modification d\'affectationReservation enregistrée avec succès', 'affectationReservation' => $affectationReservation], 200);
            } catch (\Throwable $th) {
               //throw $th;
               return response()->json(['erreur' => 'probleme dans la modification d\' un affectationReservation '], 408);
            }
         }
         return response()->json(['message' => 'affectationReservation Introuvable!'], 404);
      } catch (\Throwable $th) {
         //throw $th;
         return response()->json(['message' => 'Erreur modification affectationReservation', $th], 408);
      }
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
      try {
         $affectationReservation = AffectationReservation::where('isDeleted', false)->find($id);

         if (!$affectationReservation) {
            return response()->json(['message' => 'AffectationReservation non trouvée'], 404);
         }

         $affectationReservation->isDeleted = true;
         $affectationReservation->save();

         return response()->json(['message' => 'AffectationReservation supprimée avec succès']);
      } catch (\Throwable $th) {
         //throw $th;
         return response()->json(['message' => 'Erreur lors de la suppression de l\'affectationReservation', $th], 408);
      }
   }


public function reaffecter(Request $request)
   {
      try {         
         $affectationReservation = AffectationReservation::where('isDeleted', false)->findOrFail($request->id);
         if ($affectationReservation) {
            try {
               $affectationReservation->update([
                  'emploie_id' => $request->emploie_id,
                  'vehicule_id' => $request->vehicule_id ,
                  'status' => "Affecter"
               ]);
               return response()->json(['messages' => 'modification d\'affectationReservation enregistrée avec succès', 'affectationReservation' => $affectationReservation], 200);
            } catch (\Throwable $th) {
               //throw $th;
               return response()->json(['erreur' => 'probleme dans la modification d\' un affectationReservation '], 408);
            }
         }
         return response()->json(['message' => 'affectationReservation Introuvable!'], 404);
      } catch (\Throwable $th) {
         //throw $th;
         return response()->json(['message' => 'Erreur modification affectationReservation', $th], 408);
      }
   }
}