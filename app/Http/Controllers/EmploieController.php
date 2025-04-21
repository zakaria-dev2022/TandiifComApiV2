<?php

namespace App\Http\Controllers;

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
        $emploies=Emploie::where('isDeleted', false)->get();
        return response()->json($emploies, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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

        // Upload des fichiers

        if ($request->hasFile('copie_cin')) {
            $copieCin = $request->file('copie_cin');
            $imageName = time() . '.' . $copieCin->getClientOriginalExtension();
            $copieCin->move(public_path('images/employees/cin/'), $imageName);
            $copieCinPath = "images/employees/cin/" . $imageName;
        } else {
            $copieCinPath = "Aucun Image Entrer🙄";
        }

        if ($request->hasFile('copie_permis')) {
            $copiePermis = $request->file('copie_permis');
            $imageName = time() . '.' . $copiePermis->getClientOriginalExtension();
            $copiePermis->move(public_path('images/employees/permis/'), $imageName);
            $copiePermisPath = "images/employees/permis/" . $imageName;
        } else {
            $copiePermisPath = "Aucun Image Entrer🙄";
        }
        if ($request->hasFile('profil')) {
            $profil = $request->file('profil');
            $imageName = time() . '.' . $profil->getClientOriginalExtension();
            $profil->move(public_path('images/employees/profil/'), $imageName);
            $profilPath = "images/employees/profil/" . $imageName;
        } else {
            $profilPath = "Aucun Image Entrer🙄";
        }
        $emploie=Emploie::create([
            'nom_complet' => $request->nom_complet,
            'cin' => $request->cin,
            'tel' => $request->tel,
            'email' => $request->email,
            'copie_cin' => $copieCinPath,
            'copie_permis' => $copiePermisPath,
            'adresse' => $request->adresse,
            'profil' => $profilPath
        ]);
        return response()->json(['message' => 'nouveau emploie enregistrée avec succès','emploie' => $emploie],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $emploie=Emploie::where('isDeleted', false)->find($id);

        if ($emploie) {
            return response()->json(['emploie' => $emploie],200);
        }
        return response()->json(['message' => 'emploie Introuvable!'],404);
       
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
    
        $emploie = Emploie::where('isDeleted', false)->find($id);
        if ($emploie) {
        // Upload des fichiers

        if ($request->hasFile('copie_cin')) {
            $copieCin = $request->file('copie_cin');
            $imageName = time() . '.' . $copieCin->getClientOriginalExtension();
            $copieCin->move(public_path('images/employees/cin/'), $imageName);
            $copieCinPath = "images/employees/cin/" . $imageName;
        } else {
            $copieCinPath = "Aucun Image Entrer🙄";
        }

        if ($request->hasFile('copie_permis')) {
            $copiePermis = $request->file('copie_permis');
            $imageName = time() . '.' . $copiePermis->getClientOriginalExtension();
            $copiePermis->move(public_path('images/employees/permis/'), $imageName);
            $copiePermisPath = "images/employees/permis/" . $imageName;
        } else {
            $copiePermisPath = "Aucun Image Entrer🙄";
        }
        if ($request->hasFile('profil')) {
            $profil = $request->file('profil');
            $imageName = time() . '.' . $profil->getClientOriginalExtension();
            $profil->move(public_path('images/employees/profil/'), $imageName);
            $profilPath = "images/employees/profil/" . $imageName;
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
            'profil' => $profilPath
        ]);
        return response()->json(['message' => 'modification d\'emploie enregistrée avec succès','emploie' => $emploie],200);
    }
        return response()->json(['message' => 'emploie Introuvable!'],404);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $emploie = Emploie::find($id);

        if (!$emploie) {
            return response()->json(['message' => 'Emploie non trouvée'], 404);
        }

        $emploie->isDeleted = true;
        $emploie->save();

        return response()->json(['message' => 'Emploie supprimée avec succès']);
    
    }
}
