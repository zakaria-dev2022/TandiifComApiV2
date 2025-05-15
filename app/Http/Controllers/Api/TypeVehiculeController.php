<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Type_vehicule;
use Illuminate\Http\Request;

class TypeVehiculeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $type_vehicule = Type_vehicule::where('isDeleted', false)->get();
            return response()->json($type_vehicule, 200);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(["message" => "erreur index type_vehicule", $th], 408);
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
                    'nom' => 'required|string|max:255|unique:type_vehicules'
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
            }
            try {
                $type_vehicule = Type_vehicule::create([
                    'nom' => $request->nom
                ]);
                return response()->json(['message' => 'nouveau type_vehicule enregistrée avec succès', 'type_vehicule' => $type_vehicule], 201);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'probleme de creation d\'eun type_vehicule'], 408);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur Store Type_vehicule", $th], 408);
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
            $type_vehicule = Type_vehicule::where('isDeleted', false)->find($id);

            if ($type_vehicule) {
                return response()->json(['type_vehicule' => $type_vehicule], 200);
            }
            return response()->json(['message' => 'type_vehicule Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur show type_vehicule", $th], 408);
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
                    'nom' => 'required|string|max:255|unique:type_vehicules'
                    // 'nom' => 'required|string|max:255|unique:type_vehicules,nom,' . $id
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
            }

            $type_vehicule = Type_vehicule::where('isDeleted', false)->find($id);
            if ($type_vehicule) {
                try {
                    $type_vehicule->update([
                        'nom' => $request->nom
                    ]);
                    // $type_vehicule->save();
                    return response()->json(['message' => 'modification d\'type_vehicule enregistrée avec succès', 'type_vehicule' => $type_vehicule], 200);
                } catch (\Throwable $th) {
                    //throw $th;
                    return response()->json(['erreur' => 'probleme dans la modification d\' un type_vehicule '], 408);
                }
            }
            return response()->json(['message' => 'type_vehicule Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur modification type_vehicule', $th], 408);
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
            $type_vehicule = Type_vehicule::where('isDeleted', false)->find($id);

            if (!$type_vehicule) {
                return response()->json(['message' => 'Type_vehicule non trouvée'], 404);
            }

            $type_vehicule->isDeleted = true;
            $type_vehicule->save();

            return response()->json(['message' => 'Type_vehicule supprimée avec succès']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur lors de la suppression de l\'type_vehicule', $th], 408);
        }
    }
}
