<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DemandeDemploi;
use App\Models\Emploie;
use Illuminate\Http\Request;

class DemandeEmploiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // $demandes = DemandeDemploi::all();
            $demandes = DemandeDemploi::where('isDeleted', false)->get();
            return response()->json($demandes, 200);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(["message" => "erreur index demande d'emploie", $th], 408);
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


            //tester que les donner envoies il respecte les conditions demander (comme cin et email doit etre unique )  
            try {
                $request->validate([
                    'nom_complet' => 'required|string|max:255',
                    'cin' => 'required|string|max:20|unique:demandes_demplois',
                    'tel' => 'required|string|max:20',
                    'email' => 'required|email|unique:demandes_demplois',
                    'copie_cin' => 'required|file|mimes:pdf,jpg,png|max:2048',
                    'copie_permis' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
                    'adresse' => 'nullable|string',
                    'profil' => 'nullable|file|mimes:jpg,png|max:2048'
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'l\'un des conditions non valide '],408);
            }


            // Upload des fichiers

            if ($request->hasFile('copie_cin')) {
                $copieCin = $request->file('copie_cin');
                $imageName = time() . $request->cin . '.' . $copieCin->getClientOriginalExtension();
                $copieCin->move(public_path('images/demande_demployees/cin/'), $imageName);
                $copieCinPath = "images/demande_demployees/cin/" . $imageName;
            } else {
                $copieCinPath = "Aucun Image Entrer🙄";
            }

            if ($request->hasFile('copie_permis')) {
                $copiePermis = $request->file('copie_permis');
                $imageName = time() . $request->cin .  '.' . $copiePermis->getClientOriginalExtension();
                $copiePermis->move(public_path('images/demande_demployees/permis/'), $imageName);
                $copiePermisPath = "images/demande_demployees/permis/" . $imageName;
            } else {
                $copiePermisPath = "Aucun Image Entrer🙄";
            }
            if ($request->hasFile('profil')) {
                $profil = $request->file('profil');
                $imageName = time() . $request->cin . '.' . $profil->getClientOriginalExtension();
                $profil->move(public_path('images/demande_demployees/profil/'), $imageName);
                $profilPath = "images/demande_demployees/profil/" . $imageName;
            } else {
                $profilPath = "Aucun Image Entrer🙄";
            }

            // Création de la demande
            $demande = DemandeDemploi::create([
                'nom_complet' => $request->nom_complet,
                'cin' => $request->cin,
                'tel' => $request->tel,
                'email' => $request->email,
                'copie_cin' => $copieCinPath,
                'copie_permis' => $copiePermisPath,
                'adresse' => $request->adresse,
                'profil' => $profilPath
            ]);



            return response()->json($demande, 201);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(["message" => "erreur store demande d'emploie", $th], 408);
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
            $demande = DemandeDemploi::where("isDeleted", false)->find($id);
            if ($demande) {
                return response()->json(['demande' => $demande], 200);
            }
            return response()->json(['message' => 'demande Introuvable!'], 404);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(["message" => "erreur show demande d'emploie", $th], 408);
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
                    'nom_complet' => 'required|string|max:255',
                    'cin' => 'required|string|max:20|unique:demandes_demplois,cin,' . $id,
                    'tel' => 'required|string|max:20',
                    'email' => 'required|email|unique:demandes_demplois,email,' . $id,
                    'copie_cin' => 'required|file|mimes:pdf,jpg,png|max:2048',
                    'copie_permis' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
                    'adresse' => 'nullable|string',
                    'profil' => 'nullable|file|mimes:jpg,png|max:2048'
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'l\'un des conditions non valide '],408);
            }

            $demande = DemandeDemploi::where('isDeleted', false)->findOrFail($id);
            // Upload des fichiers

            if ($demande) {


                if ($request->hasFile('copie_cin')) {
                    $copieCin = $request->file('copie_cin');
                    $imageName = time() . $request->cin . '.' . $copieCin->getClientOriginalExtension();
                    $copieCin->move(public_path('images/demande_demployees/cin/'), $imageName);
                    $copieCinPath = "images/demande_demployees/cin/" . $imageName;
                } else {
                    $copieCinPath = "Aucun Image Entrer🙄";
                }



                if ($request->hasFile('copie_permis')) {
                    $copiePermis = $request->file('copie_permis');
                    $imageName = time() . $request->cin . '.' . $copiePermis->getClientOriginalExtension();
                    $copiePermis->move(public_path('images/demande_demployees/permis/'), $imageName);
                    $copiePermisPath = "images/demande_demployees/permis/" . $imageName;
                } else {
                    $copiePermisPath = "Aucun Image Entrer🙄";
                }



                if ($request->hasFile('profil')) {
                    $profil = $request->file('profil');
                    $imageName = time() . $request->cin . '.' . $profil->getClientOriginalExtension();
                    $profil->move(public_path('images/demande_demployees/profil/'), $imageName);
                    $profilPath = "images/demande_demployees/profil/" . $imageName;
                } else {
                    $profilPath = "Aucun Image Entrer🙄";
                }

                $demande->update([
                    'nom_complet' => $request->nom_complet,
                    'cin' => $request->cin,
                    'tel' => $request->tel,
                    'email' => $request->email,
                    'copie_cin' => $copieCinPath,
                    'copie_permis' => $copiePermisPath,
                    'adresse' => $request->adresse,
                    'profil' => $profilPath

                ]);

                return response()->json($demande, 200);
            } else {
                return response()->json(["message" => "Aucune demande trouvée"], 404);
            }
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(["message" => "erreur modification demande d'emploie", $th], 408);
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
            $demande = DemandeDemploi::where('isDeleted', false)->find($id);

            if (!$demande) {
                return response()->json(['message' => 'Demande non trouvée'], 404);
            }

            $demande->isDeleted = true;
            $demande->save();

            return response()->json(['message' => 'Demande supprimée avec succès']);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(["message" => "erreur suppression demande d'emploie", $th], 408);
        }
    }

    //on vas ajouter une fonction pour envoyer les donnees du table demande d'emploie a la table laveur
    public function accepterDemande($id)
    {
        try {
            $demande = DemandeDemploi::where('isDeleted', false)->find($id);
            if (!$demande) {
                return response()->json(['message' => 'Demande non trouvée'], 404);
            }

            try {
                $demande->validate([
                    'nom_complet' => 'required|string|max:255',
                    'cin' => 'required|string|max:20|unique:emploies,cin',
                    'tel' => 'required|string|max:20',
                    'email' => 'required|email|unique:emploies,email',
                    'copie_cin' => 'required|file|mimes:pdf,jpg,png|max:2048',
                    'copie_permis' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
                    'adresse' => 'nullable|string',
                    'profil' => 'nullable|file|mimes:jpg,png|max:2048'
                ]);


                $demande = Emploie::create([
                    'nom_complet' => $demande->nom_complet,
                    'cin' => $demande->cin,
                    'tel' => $demande->tel,
                    'email' => $demande->email,
                    'copie_cin' => $demande->copie_cin,
                    'copie_permis' => $demande->copie_permis,
                    'adresse' => $demande->adresse,
                    'profil' => $demande->profil
                ]);
                return response()->json(['message' => 'Donnees envoyées avec succès'], 200);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'l\'un des conditions non valide '],408);
            }
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(["message" => "erreur acceptation demande d'emploie", $th], 408);
        }
    }
}
