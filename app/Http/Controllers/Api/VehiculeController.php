<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicule;
use Illuminate\Http\Request;

class VehiculeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  try {
        //     $vehicule = Vehicule::where('isDeleted', false)->get();
        //     return response()->json($vehicule, 200);
        // } catch (\Throwable $th) {
        // //     // throw $th;
        //     return response()->json(["message" => "erreur index vehicule", $th], 408);
        // }

        try {
            $vehicule = Vehicule::with('type') // charge les données liées
                ->where('isDeleted', false)
                ->get();

            // Optionnel : cacher la clé étrangère et ne retourner que le nom
            $vehicule = $vehicule->map(function ($v) {
                return [
                    'id' => $v->id,
                    'marque' => $v->marque,
                    'type_vehicule_id' => $v->type->id, 
                    'type_vehicule_nom' => $v->type->nom, 
                    'matricule' => $v->matricule,
                    'status' => $v->status, 
                    'image' => $v->image, 
                    'created_at' => $v->created_at,
                    'updated_at' => $v->updated_at,
                ];
            });

            return response()->json($vehicule, 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "erreur index vehicule", "error" => $th->getMessage()], 408);
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
                    'marque' => 'required|string|max:255',
                    // 'type_vehicule' => 'required|exists:type_vehicules,id',
                    'matricule' => 'required|string|max:255|unique:vehicules',
                    'status' => 'required|string|max:255',
                    'image' => ['nullable', function ($attribute, $value, $fail) {
                        if (is_string($value)) {
                            if (strlen($value) > 255) {
                                $fail('L’image en tant que chaîne de caractères ne doit pas dépasser 255 caractères.');
                            }
                        } elseif ($value instanceof \Illuminate\Http\UploadedFile) {
                            if (!in_array($value->getClientOriginalExtension(), ['jpg', 'png'])) {
                                $fail('Le fichier image doit être au format jpg ou png.');
                            }
                            if ($value->getSize() > 2048 * 1024) {
                                $fail('Le fichier image ne doit pas dépasser 2 Mo.');
                            }
                        } 
                    }],            
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
            }

            // Upload des fichiers

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . ' ' . $request->matricule . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/vehicules/'), $imageName);
                $imagePath = "images/vehicules/" . $imageName;
            } elseif($request->image) {

                    $imagePath = $request->image;
                }else{

                    $imagePath = "Aucun Image Entrer🙄";
                }   

            try {
                $vehicule = Vehicule::create([
                    'marque' => $request->marque,
                    'type_id' => $request->type_vehicule,
                    'matricule' => $request->matricule,
                    'status' => $request->status,
                    'image' => $imagePath
                ]);
                return response()->json(['message' => 'nouveau vehicule enregistrée avec succès', 'vehicule' => $vehicule], 201);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'probleme de creation d\'eun vehicule'], 408);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur Store Vehicule", $th], 408);
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
            $vehicule = Vehicule::where('isDeleted', false)->find($id);

            if ($vehicule) {
                return response()->json(['vehicule' => $vehicule], 200);
            }
            return response()->json(['message' => 'vehicule Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur show vehicule", $th], 408);
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
                    'marque' => 'required|string|max:255',
                    'type_vehicule' => 'required|exists:type_vehicules,id',
                    'matricule' => 'required|string|max:255|unique:vehicules,matricule,' . $id,
                    'status' => 'required|string|max:255',
                    'image' => ['nullable', function ($attribute, $value, $fail) {
                        if (is_string($value)) {
                            if (strlen($value) > 255) {
                                $fail('L’image en tant que chaîne de caractères ne doit pas dépasser 255 caractères.');
                            }
                        } elseif ($value instanceof \Illuminate\Http\UploadedFile) {
                            if (!in_array($value->getClientOriginalExtension(), ['jpg', 'png'])) {
                                $fail('Le fichier image doit être au format jpg ou png.');
                            }
                            if ($value->getSize() > 2048 * 1024) {
                                $fail('Le fichier image ne doit pas dépasser 2 Mo.');
                            }
                        } 
                    }],
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
            }

            $vehicule = Vehicule::where('isDeleted', false)->find($id);
            if ($vehicule) {
                // Upload des fichiers

                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . ' ' . $request->nom . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images/vehicules/'), $imageName);
                    $imagePath = "images/vehicules/" . $imageName;
                } elseif($request->image) {

                    $imagePath = $request->image;
                }else{

                    $imagePath = "Aucun Image Entrer🙄";
                }
                try {
                    $vehicule->update([
                        'marque' => $request->marque,
                        'type_id' => $request->type_vehicule,
                        'matricule' => $request->matricule,
                        'status' => $request->status,
                        'image' => $imagePath
                    ]);
                    // $vehicule->save();
                    return response()->json(['message' => 'modification d\'vehicule enregistrée avec succès', 'vehicule' => $vehicule], 200);
                } catch (\Throwable $th) {
                    //throw $th;
                    return response()->json(['erreur' => 'probleme dans la modification d\' un vehicule '], 408);
                }
            }
            return response()->json(['message' => 'vehicule Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur modification vehicule', $th], 408);
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
            $vehicule = Vehicule::where('isDeleted', false)->find($id);

            if (!$vehicule) {
                return response()->json(['message' => 'Vehicule non trouvée'], 404);
            }

            $vehicule->isDeleted = true;
            $vehicule->save();

            return response()->json(['message' => 'Vehicule supprimée avec succès']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur lors de la suppression de l\'vehicule', $th], 408);
        }
    }
}
