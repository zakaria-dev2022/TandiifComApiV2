<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use Illuminate\Http\Request;

class CommentaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $commentaire = Commentaire::where('isDeleted', false)->get();
            return response()->json($commentaire, 200);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(["message" => "erreur index commentaire", $th], 408);
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
                    'client_id' => 'required|exists:clients,id',
                    'commentaire' => 'required|string|max:255',
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
            }

            try {
                $commentaire = Commentaire::create([
                    'client_id' => $request->client_id,
                    'commentaire' => $request->commentaire,
                ]);
                return response()->json(['message' => 'nouveau commentaire enregistrée avec succès', 'commentaire' => $commentaire], 201);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'probleme de creation d\'une commentaire', $th], 408);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur Store Commentaire", $th], 408);
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
            $commentaire = Commentaire::where('isDeleted', false)->find($id);

            if ($commentaire) {
                return response()->json(['commentaire' => $commentaire], 200);
            }
            return response()->json(['message' => 'commentaire Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur show commentaire", $th], 408);
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
                    'client_id' => 'required|exists:clients,id',
                    'commentaire' => 'required|string|max:255',
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
            }

            $commentaire = Commentaire::where('isDeleted', false)->find($id);
            if ($commentaire) {
                try {
                    $commentaire->update([
                        'client_id' => $request->client_id,
                        'commentaire' => $request->commentaire,
                    ]);
                    return response()->json(['message' => 'modification d\'commentaire enregistrée avec succès', 'commentaire' => $commentaire], 200);
                } catch (\Throwable $th) {
                    //throw $th;
                    return response()->json(['erreur' => 'probleme dans la modification d\' un commentaire '], 408);
                }
            }
            return response()->json(['message' => 'commentaire Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur modification commentaire', $th], 408);
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
            $commentaire = Commentaire::where('isDeleted', false)->find($id);

            if (!$commentaire) {
                return response()->json(['message' => 'Commentaire non trouvée'], 404);
            }

            $commentaire->isDeleted = true;
            $commentaire->save();

            return response()->json(['message' => 'Commentaire supprimée avec succès']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur lors de la suppression de l\'commentaire', $th], 408);
        }
    
    }
}
