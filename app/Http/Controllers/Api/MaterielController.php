<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Materiel;
use Illuminate\Http\Request;

class MaterielController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //   try {
        //     $materiel = Materiel::where('isDeleted', false)->get();
        //     return response()->json($materiel, 200);
        // } catch (\Throwable $th) {
        //     // throw $th;
        //     return response()->json(["message" => "erreur index materiel", $th], 408);
        // }
        try {
            $materiel = Materiel::with('typeMateriel') // charge les données liées
                ->where('isDeleted', false)
                ->get();

            // Optionnel : cacher la clé étrangère et ne retourner que le nom
            $materiel = $materiel->map(function ($v) {
                return [
                    'id' => $v->id,
                    'nom' => $v->nom,
                    'type_materiel_id' => $v->typeMateriel->id, 
                    'type_materiel_nom' => $v->typeMateriel->nom, 
                    'description' => $v->description,
                    'qte' => $v->qte, 
                    'image' => $v->image, 
                    'created_at' => $v->created_at,
                    'updated_at' => $v->updated_at,
                ];
            });

            return response()->json($materiel, 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "erreur index materiel", "error" => $th->getMessage()], 408);
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
                    'nom' => 'required|string|max:255',
                    'type_materiel_id' => 'required|exists:type_materiels,id',
                    'description' => 'required|string',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                    'qte' => 'required|integer|min:0',
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
            }


            // Upload des fichiers

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . ' ' . $request->nom . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/materiels/'), $imageName);
                $imagePath = "images/materiels/" . $imageName;
            } elseif($request->image) {

                    $imagePath = $request->image;
                }else{

                    $imagePath = "Aucun Image Entrer🙄";
                }

            try {
                $materiel = Materiel::create([
                    'nom' => $request->nom,
                    'type_materiel_id' => $request->type_materiel_id,
                    'description' => $request->description,
                    'image' => $imagePath,
                    'qte' => $request->qte,

                ]);
                return response()->json(['message' => 'nouveau materiel enregistrée avec succès', 'materiel' => $materiel], 201);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'probleme de creation d\'eun materiel'], 408);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur Store Materiel", $th], 408);
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
            $materiel = Materiel::where('isDeleted', false)->find($id);

            if ($materiel) {
                return response()->json(['materiel' => $materiel], 200);
            }
            return response()->json(['message' => 'materiel Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur show materiel", $th], 408);
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
                    'nom' => 'required|string|max:255',
                    'type_materiel_id' => 'required|exists:type_materiels,id',
                    'description' => 'required|string',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                    'qte' => 'required|integer|min:0',
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
            }

            $materiel = Materiel::where('isDeleted', false)->find($id);
            if ($materiel) {
                // Upload des fichiers

                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . ' ' . $request->nom . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images/materiels/'), $imageName);
                    $imagePath = "images/materiels/" . $imageName;
                } elseif($request->image) {

                    $imagePath = $request->image;
                }else{

                    $imagePath = "Aucun Image Entrer🙄";
                }
                try {
                    $materiel->update([
                        'nom' => $request->nom,
                        'type_materiel_id' => $request->type_materiel_id,
                        'description' => $request->description,
                        'image' => $imagePath,
                        'qte' => $request->qte,
                    ]);
                    // $materiel->save();
                    return response()->json(['message' => 'modification du materiel enregistrée avec succès', 'materiel' => $materiel], 200);
                } catch (\Throwable $th) {
                    //throw $th;
                    return response()->json(['erreur' => 'probleme dans la modification d\' un materiel '], 408);
                }
            }
            return response()->json(['message' => 'materiel Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur modification materiel', $th], 408);
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
            $materiel = Materiel::where('isDeleted', false)->find($id);

            if (!$materiel) {
                return response()->json(['message' => 'Materiel non trouvée'], 404);
            }

            $materiel->isDeleted = true;
            $materiel->save();

            return response()->json(['message' => 'Materiel supprimée avec succès']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur lors de la suppression de l\'materiel', $th], 408);
        }
    }
}
