<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AffectationReservation;
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
         $affectationReservations = AffectationReservation::where('isDeleted', false)->get();
         return response()->json($affectationReservations, 200);
      } catch (\Throwable $th) {
         //throw $th;
         return response()->json(['error' => 'An error index affectationReservation.', $th], 500);
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


         try {
            $affectationReservation = AffectationReservation::create([
               'emploie_id' => $request->emploie_id,
               'reservation_id' => $request->reservation_id,
               'vehicule_id' => $request->vehicule_id,
               'date_affectation' => $request->date_affectation,
               'status' => $request->status,
               'commentaire_emploie' => $request->commentaire_emploie

            ]);
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
         try {
         $affectationReservation = AffectationReservation::where('IsDeleted', false)->find($id);
         if ($affectationReservation) {
            return response()->json(['message' => $affectationReservation], 200);
         } else {
            return response()->json(['message' => 'AffectationReservation not found.'], 404);
         }
      } catch (\Throwable $th) {
         //throw $th;
         return response()->json(['error' => 'An error show affectationReservation.', $th], 500);
      }
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
}
