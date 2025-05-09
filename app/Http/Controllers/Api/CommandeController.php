<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $commande = Commande::where('isDeleted', false)->get();
            return response()->json($commande, 200);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(["message" => "erreur index commande", $th], 408);
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
                    'client_id' => 'required',
                    'article_id' => 'required',
                    // 'date_commande' => 'nullable|date',
                    'qte' => 'integer|min:1',
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
            }

            try {
                $commande = Commande::create([
                    'client_id' => $request->client_id,
                    'article_id' => $request->article_id,
                    // 'date_commande' => $request->date_commande,
                    'qte' => $request->qte

                ]);
                return response()->json(['message' => 'nouveau commande enregistrée avec succès', 'commande' => $commande], 201);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'probleme de creation d\'eun commande', $th], 408);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur Store Commande", $th], 408);
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
            $commande = Commande::where('isDeleted', false)->find($id);

            if ($commande) {
                return response()->json(['commande' => $commande], 200);
            }
            return response()->json(['message' => 'commande Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur show commande", $th], 408);
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
                    'client_id' => 'required',
                    'article_id' => 'required',
                    // 'date_commande' => 'nullable|date',
                    'qte' => 'integer|min:1',
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
            }

            $commande = Commande::where('isDeleted', false)->find($id);
            if ($commande) {
                try {
                    $commande->update([
                        'client_id' => $request->client_id,
                        'article_id' => $request->article_id,
                        // 'date_commande' => $request->date_commande,
                        'qte' => $request->qte
                    ]);
                    return response()->json(['message' => 'modification d\'commande enregistrée avec succès', 'commande' => $commande], 200);
                } catch (\Throwable $th) {
                    //throw $th;
                    return response()->json(['erreur' => 'probleme dans la modification d\' un commande '], 408);
                }
            }
            return response()->json(['message' => 'commande Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur modification commande', $th], 408);
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
            $commande = Commande::where('isDeleted', false)->find($id);

            if (!$commande) {
                return response()->json(['message' => 'Commande non trouvée'], 404);
            }

            $commande->isDeleted = true;
            $commande->save();

            return response()->json(['message' => 'Commande supprimée avec succès']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur lors de la suppression de l\'commande', $th], 408);
        }
    }
}
