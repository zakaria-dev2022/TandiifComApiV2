<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Emploie;
use Illuminate\Http\Request;

class EmploieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $emploies = Emploie::where('isDeleted', false)->get();
            return response()->json($emploies, 200);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(["message" => "erreur index emploie", $th], 408);
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
            // try {
            //     $request->validate([
            //         'nom_complet' => 'required|string|max:255',
            //         // 'cin' => 'required|string|max:20|unique:emploies,cin',
            //         'tel' => 'required|string|max:20',
            //         // 'email' => 'required|email|unique:emploies,email',
            //         'copie_cin' => ['nullable', function ($attribute, $value, $fail) {
            //             if (is_string($value)) {
            //                 if (strlen($value) > 255) {
            //                     $fail('L’image en tant que chaîne de caractères ne doit pas dépasser 255 caractères.');
            //                 }
            //             } elseif ($value instanceof \Illuminate\Http\UploadedFile) {
            //                 if (!in_array($value->getClientOriginalExtension(), ['pdf'])) {
            //                     $fail('Le fichier image doit être au format jpg ou png.');
            //                 }
            //                 if ($value->getSize() > 2048 * 1024) {
            //                     $fail('Le fichier image ne doit pas dépasser 2 Mo.');
            //                 }
            //             }
            //         }],
            //         'copie_permis' => ['nullable', function ($attribute, $value, $fail) {
            //             if (is_string($value)) {
            //                 if (strlen($value) > 255) {
            //                     $fail('L’image en tant que chaîne de caractères ne doit pas dépasser 255 caractères.');
            //                 }
            //             } elseif ($value instanceof \Illuminate\Http\UploadedFile) {
            //                 if (!in_array($value->getClientOriginalExtension(), ['pdf'])) {
            //                     $fail('Le fichier image doit être au format jpg ou png.');
            //                 }
            //                 if ($value->getSize() > 2048 * 1024) {
            //                     $fail('Le fichier image ne doit pas dépasser 2 Mo.');
            //                 }
            //             }
            //         }],
            //         'adresse' => 'nullable|string',
            //         'status' => 'nullable|string',
            //         'profil' => 'nullable|file|mimes:jpg,png|max:2048'
            //     ]);
            // } catch (\Throwable $th) {
            //     //throw $th;
            //     return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
            // }
            // Upload des fichiers

            if ($request->hasFile('copie_cin')) {
                $copieCin = $request->file('copie_cin');
                $imageName = time() . ' ' . $request->cin . '.' . $copieCin->getClientOriginalExtension();
                $copieCin->move(public_path('images/employees/cin/'), $imageName);
                $copieCinPath = "images/employees/cin/" . $imageName;
            } elseif ($request->copie_cin) {

                $copieCinPath = $request->copie_cin;
            } else {

                $copieCinPath = "Aucun Image Entrer🙄";
            }

            if ($request->hasFile('copie_permis')) {
                $copiePermis = $request->file('copie_permis');
                $imageName = time() . ' ' . $request->cin . '.' . $copiePermis->getClientOriginalExtension();
                $copiePermis->move(public_path('images/employees/permis/'), $imageName);
                $copiePermisPath = "images/employees/permis/" . $imageName;
            } elseif ($request->copie_permis) {

                $copiePermisPath = $request->copie_permis;
            } else {

                $copiePermisPath = "Aucun Image Entrer🙄";
            }
            if ($request->hasFile('profil')) {
                $profil = $request->file('profil');
                $imageName = time() . ' ' . $request->cin . '.' . $profil->getClientOriginalExtension();
                $profil->move(public_path('images/employees/profil/'), $imageName);
                $profilPath = "images/employees/profil/" . $imageName;
            } elseif ($request->profil) {

                $profilPath = $request->profil;
            } else {

                $profilPath = "Aucun Image Entrer🙄";
            }
            try {

                $emploie = Emploie::create([
                    'nom_complet' => $request->nom_complet,
                    'cin' => $request->cin,
                    'tel' => $request->tel,
                    'email' => $request->email,
                    'copie_cin' => $copieCinPath,
                    'copie_permis' => $copiePermisPath,
                    'adresse' => $request->adresse,
                    'profil' => $profilPath
                ]);
                return response()->json(['message' => 'nouveau emploie enregistrée avec succès', 'emploie' => $emploie], 201);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'probleme dans la creation d\'emploie ', $th], 408);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur Store Emploie", $th], 408);
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
            $emploie = Emploie::where('isDeleted', false)->find($id);

            if ($emploie) {
                return response()->json(['emploie' => $emploie], 200);
            }
            return response()->json(['message' => 'emploie Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur show emploie", $th], 408);
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
            $request->validate([
                'nom_complet' => 'required|string|max:255',
                'cin' => 'required|string|max:20|unique:emploies,cin,' . $id,
                'tel' => 'required|string|max:20',
                'email' => 'required|email|unique:emploies,email,' . $id,
                'copie_cin' => ['nullable', function ($attribute, $value, $fail) {
                    if (is_string($value)) {
                        if (strlen($value) > 255) {
                            $fail('L’image en tant que chaîne de caractères ne doit pas dépasser 255 caractères.');
                        }
                    } elseif ($value instanceof \Illuminate\Http\UploadedFile) {
                        if (!in_array($value->getClientOriginalExtension(), ['pdf'])) {
                            $fail('Le fichier image doit être au format jpg ou png.');
                        }
                        if ($value->getSize() > 2048 * 1024) {
                            $fail('Le fichier image ne doit pas dépasser 2 Mo.');
                        }
                    }
                }],
                'copie_permis' => ['nullable', function ($attribute, $value, $fail) {
                    if (is_string($value)) {
                        if (strlen($value) > 255) {
                            $fail('L’image en tant que chaîne de caractères ne doit pas dépasser 255 caractères.');
                        }
                    } elseif ($value instanceof \Illuminate\Http\UploadedFile) {
                        if (!in_array($value->getClientOriginalExtension(), ['pdf'])) {
                            $fail('Le fichier image doit être au format jpg ou png.');
                        }
                        if ($value->getSize() > 2048 * 1024) {
                            $fail('Le fichier image ne doit pas dépasser 2 Mo.');
                        }
                    }
                }],
                'adresse' => 'nullable|string',
                'profil' => ['nullable', function ($attribute, $value, $fail) {
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

            $emploie = Emploie::where('isDeleted', false)->find($id);
            if ($emploie) {
                // Upload des fichiers

                if ($request->hasFile('copie_cin')) {
                    $copieCin = $request->file('copie_cin');
                    $imageName = time() . ' ' . $request->cin . '.' . $copieCin->getClientOriginalExtension();
                    $copieCin->move(public_path('images/employees/cin/'), $imageName);
                    $copieCinPath = "images/employees/cin/" . $imageName;
                } elseif ($request->copie_cin) {

                    $copieCinPath = $request->copie_cin;
                } else {

                    $copieCinPath = "Aucun Image Entrer🙄";
                }

                if ($request->hasFile('copie_permis')) {
                    $copiePermis = $request->file('copie_permis');
                    $imageName = time() . ' ' . $request->cin . '.' . $copiePermis->getClientOriginalExtension();
                    $copiePermis->move(public_path('images/employees/permis/'), $imageName);
                    $copiePermisPath = "images/employees/permis/" . $imageName;
                } elseif ($request->copie_permis) {

                    $copiePermisPath = $request->copie_permis;
                } else {

                    $copiePermisPath = "Aucun Image Entrer🙄";
                }
                if ($request->hasFile('profil')) {
                    $profil = $request->file('profil');
                    $imageName = time() . ' ' . $request->cin . '.' . $profil->getClientOriginalExtension();
                    $profil->move(public_path('images/employees/profil/'), $imageName);
                    $profilPath = "images/employees/profil/" . $imageName;
                } elseif ($request->profil) {

                    $profilPath = $request->profil;
                } else {

                    $profilPath = "Aucun Image Entrer🙄";
                }


                $emploie->update([
                    'nom_complet' => $request->nom_complet,
                    'cin' => $request->cin,
                    'tel' => $request->tel,
                    'email' => $request->email,
                    'copie_cin' => $copieCinPath,
                    'copie_permis' => $copiePermisPath,
                    'adresse' => $request->adresse,
                    'status' => $request->status,
                    'profil' => $profilPath
                ]);

                // $emploie->save();
                return response()->json(['message' => 'modification d\'emploie enregistrée avec succès', 'emploie' => $emploie], 200);
            }
            return response()->json(['message' => 'emploie Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur modification emploie', $th], 408);
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
            $emploie = Emploie::where('isDeleted', false)->find($id);

            if ($emploie) {
                $emploie->isDeleted = true;
                $emploie->save();
                return response()->json(['message' => 'Emploie supprimée avec succès']);
            }
            return response()->json(['message' => 'Emploie non trouvée'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur lors de la suppression de l\'emploie', $th], 408);
        }
    }
}
