<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      try {
         $clients = Client::where('isDeleted', false)->get();
         return response()->json($clients, 200);
      } catch (\Throwable $th) {
         //throw $th;
         return response()->json(['error' => 'An error index client.', $th], 500);
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
               'nom_complet' => 'required|string|max:255',
               // 'cin' => 'required|string|max:20|unique:clients',
               'tel' => 'required|string|max:20',
               'email' => 'required|email|unique:clients',
               // 'adresse' => 'required|string',
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
         } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
         }

         if ($request->hasFile('profil')) {
            $profil = $request->file('profil');
            $imageName = time() . $request->cin . '.' . $profil->getClientOriginalExtension();
            $profil->move(public_path('images/clients/'), $imageName);
            $profilPath = "images/clients/" . $imageName;
         } elseif ($request->profil) {
            $profilPath = $request->profil;
         } else {

            $profilPath = "Aucun Image Entrer🙄";
         }
         try {
            $client = Client::create([
               'nom_complet' => $request->nom_complet,
               // 'cin' => $request->cin,
               'tel' => $request->tel,
               'email' => $request->email,
               // 'adresse' => $request->adresse,
               'profil' => $profilPath
            ]);
            return response()->json(['message' => $client], 201);
         } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['erreur' => 'probleme dans la creation de client ', $th], 408);
         }
      } catch (\Throwable $th) {
         //throw $th;
         return response()->json(['error' => 'An error store client.', $th], 500);
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
         $client = Client::where('IsDeleted', false)->find($id);
         if ($client) {
            return response()->json(['message' => $client], 200);
         } else {
            return response()->json(['message' => 'Client not found.'], 404);
         }
      } catch (\Throwable $th) {
         //throw $th;
         return response()->json(['error' => 'An error show client.', $th], 500);
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
               // 'cin' => 'required|string|max:20|unique:clients,cin,' . $id,
               'tel' => 'required|string|max:20',
               'email' => 'required|email|unique:clients,email,' . $id,
               // 'adresse' => 'required|string',
               // 'profil' => 'nullable|file|mimes:jpg,png|max:2048',

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
         } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
         }
         $client = Client::where('isDeleted', false)->find($id);
         if ($client) {
            // Upload de fichier

            if ($request->hasFile('profil')) {
               $profil = $request->file('profil');
               $imageName = time() . $request->cin . '.' . $profil->getClientOriginalExtension();
               $profil->move(public_path('images/clients/'), $imageName);
               $profilPath = "images/clients/" . $imageName;
            } elseif ($request->profil) {

               $profilPath = $request->profil;
            } else {

               $profilPath = "Aucun Image Entrer🙄";
            }
            try {
               $client->update([
                  'nom_complet' => $request->nom_complet,
                  // 'cin' => $request->cin,
                  'tel' => $request->tel,
                  'email' => $request->email,
                  // 'adresse' => $request->adresse,
                  'profil' => $profilPath
               ]);

               return response()->json(['message' => 'Client updated successfully.'], 200);
            } catch (\Throwable $th) {
               //throw $th;
               return response()->json(['erreur' => 'probleme dans la modification de client '], 408);
            }
         }
         return response()->json(['message' => 'Client not found.'], 404);
      } catch (\Throwable $th) {
         //throw $th;
         return response()->json(['error' => 'An error update client.', $th], 500);
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
         $client = Client::where('isDeleted', false)->find($id);

         if (!$client) {
            return response()->json(['message' => 'Client non trouvée'], 404);
         }

         $client->isDeleted = true;
         $client->save();

         return response()->json(['message' => 'Client supprimée avec succès']);
      } catch (\Throwable $th) {
         //throw $th;
         return response()->json(['error' => 'An error destroy client.', $th], 500);
      }
   }
}
