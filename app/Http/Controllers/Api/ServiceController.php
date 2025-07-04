<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $service = Service::where('isDeleted', false)->get();
            return response()->json($service, 200);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(["message" => "erreur index service", $th], 408);
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
                    'nom' => 'required|string|max:255|unique:services',
                    'description' => 'required|string',
                    'prix' => 'required|numeric',
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
                $imageName = time() . ' ' . $request->nom . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/services/'), $imageName);
                $imagePath = "images/services/" . $imageName;
            } elseif($request->image) {

                    $imagePath = $request->image;
                }else{

                    $imagePath = "Aucun Image Entrer🙄";
                }

            try {
                $service = Service::create([
                    'nom' => $request->nom,
                    'description' => $request->description,
                    'prix' => $request->prix,
                    'image' => $imagePath

                ]);
                return response()->json(['message' => 'nouveau service enregistrée avec succès', 'service' => $service], 201);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'probleme de creation d\'eun service'], 408);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur Store Service", $th], 408);
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
            $service = Service::where('isDeleted', false)->find($id);

            if ($service) {
                return response()->json(['service' => $service], 200);
            }
            return response()->json(['message' => 'service Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur show service", $th], 408);
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
                // $request->validate([
                //     // 'nom' => 'required|string|max:255|unique:services',
                //     'nom' => 'required|string|max:255|unique:services,nom,' . $id,
                //     'description' => 'required|string',
                //     'prix' => 'required|numeric',
                //     'image' => 'nullable|string|file|mimes:jpg,png|max:2048'
                // ]);
                $request->validate([
                    'nom' => 'required|string|max:255|unique:services,nom,' . $id,
                    'description' => 'required|string',
                    'prix' => 'required|numeric',
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

            $service = Service::where('isDeleted', false)->find($id);
            if ($service) {
                // Upload des fichiers

                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . ' ' . $request->nom . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images/services/'), $imageName);
                    $imagePath = "images/services/" . $imageName;
                } 
                elseif($request->image) {

                    $imagePath = $request->image;
                }else{

                    $imagePath = "Aucun Image Entrer🙄";
                }

                try {
                    $service->update([
                        'nom' => $request->nom,
                        'description' => $request->description,
                        'prix' => $request->prix,
                        'image' => $imagePath
                    ]);
                    // $service->save();
                    return response()->json(['message' => 'modification d\'service enregistrée avec succès', 'service' => $service], 200);
                } catch (\Throwable $th) {
                    //throw $th;
                    return response()->json(['erreur' => 'probleme dans la modification d\' un service '], 408);
                }
            }
            return response()->json(['message' => 'service Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur modification service', $th], 408);
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
            $service = Service::where('isDeleted', false)->find($id);

            if (!$service) {
                return response()->json(['message' => 'Service non trouvée'], 404);
            }

            $service->isDeleted = true;
            $service->save();

            return response()->json(['message' => 'Service supprimée avec succès']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur lors de la suppression de l\'service', $th], 408);
        }
    }
}
