<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AffectationCommande;
use Illuminate\Http\Request;

class AffectationCommandeController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      try {
         $affectationCommandes = AffectationCommande::where('isDeleted', false)->get();
         return response()->json($affectationCommandes, 200);
      } catch (\Throwable $th) {
         //throw $th;
         return response()->json(['error' => 'An error index affectationCommande.', $th], 500);
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
               'commande_id' => 'required|exists:commande,id',
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
            $affectationCommande = AffectationCommande::create([
               'emploie_id' => $request->emploie_id,
               'commande_id' => $request->commande_id,
               'vehicule_id' => $request->vehicule_id,
               'date_affectation' => $request->date_affectation,
               'status' => $request->status,
               'commentaire_emploie' => $request->commentaire_emploie

            ]);
            return response()->json(['message' => $affectationCommande], 201);
         } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['erreur' => 'problemedans la creation de affectationCommande '], 408);
         }
      } catch (\Throwable $th) {
         //throw $th;
         return response()->json(['error' => 'An error store affectationCommande.', $th], 500);
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
         $affectationCommande = AffectationCommande::where('IsDeleted', false)->find($id);
         if ($affectationCommande) {
            return response()->json(['message' => $affectationCommande], 200);
         } else {
            return response()->json(['message' => 'AffectationCommande not found.'], 404);
         }
      } catch (\Throwable $th) {
         //throw $th;
         return response()->json(['error' => 'An error show affectationCommande.', $th], 500);
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
               'commande_id' => 'required|exists:commande,id',
               'vehicule_id' => 'required|exists:vehicule,id',
               'date_affectation' => 'required|date',
               'status' => 'required|string|max:55',
               'commentaire_emploie' => 'nullable|string|max:1000',
            ]);
         } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
         }

         $affectationCommande = AffectationCommande::where('isDeleted', false)->find($id);
         if ($affectationCommande) {
            try {
               $affectationCommande->update([
                  'emploie_id' => $request->emploie_id,
                  'commande_id' => $request->commande_id,
                  'vehicule_id' => $request->vehicule_id,
                  'date_affectation' => $request->date_affectation,
                  'status' => $request->status,
                  'commentaire_emploie' => $request->commentaire_emploie
               ]);
               return response()->json(['message' => 'modification d\'affectationCommande enregistrée avec succès', 'affectationCommande' => $affectationCommande], 200);
            } catch (\Throwable $th) {
               //throw $th;
               return response()->json(['erreur' => 'probleme dans la modification d\' un affectationCommande '], 408);
            }
         }
         return response()->json(['message' => 'affectationCommande Introuvable!'], 404);
      } catch (\Throwable $th) {
         //throw $th;
         return response()->json(['message' => 'Erreur modification affectationCommande', $th], 408);
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
            $affectationCommande = AffectationCommande::where('isDeleted', false)->find($id);

            if (!$affectationCommande) {
                return response()->json(['message' => 'AffectationCommande non trouvée'], 404);
            }

            $affectationCommande->isDeleted = true;
            $affectationCommande->save();

            return response()->json(['message' => 'AffectationCommande supprimée avec succès']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur lors de la suppression de l\'affectationCommande', $th], 408);
        }
   }
}
