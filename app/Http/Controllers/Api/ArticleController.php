<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $article = Article::where('isDeleted', false)->get();
            return response()->json($article, 200);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(["message" => "erreur index article", $th], 408);
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
                    'nom' => 'required|string|max:255|unique:articles',
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
                $image->move(public_path('images/articles/'), $imageName);
                $imagePath = "images/articles/" . $imageName;
            } elseif($request->image) {

                    $imagePath = $request->image;
                }else{

                    $imagePath = "Aucun Image Entrer🙄";
                }

            try {
                $article = Article::create([
                    'nom' => $request->nom,
                    'description' => $request->description,
                    'prix' => $request->prix,
                    'image' => $imagePath

                ]);
                return response()->json(['message' => 'nouveau article enregistrée avec succès', 'article' => $article], 201);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'probleme de creation d\'eun article'], 408);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur Store Article", $th], 408);
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
            $article = Article::where('isDeleted', false)->find($id);

            if ($article) {
                return response()->json(['article' => $article], 200);
            }
            return response()->json(['message' => 'article Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur show article", $th], 408);
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
                    // 'nom' => 'required|string|max:255|unique:articles',
                    'nom' => 'required|string|max:255|unique:articles,nom,' . $id,
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

            $article = Article::where('isDeleted', false)->find($id);
            if ($article) {
                // Upload des fichiers

                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . ' ' . $request->nom . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images/articles/'), $imageName);
                    $imagePath = "images/articles/" . $imageName;
                } elseif($request->image) {

                    $imagePath = $request->image;
                }else{

                    $imagePath = "Aucun Image Entrer🙄";
                }
                try {
                    $article->update([
                        'nom' => $request->nom,
                        'description' => $request->description,
                        'prix' => $request->prix,
                        'image' => $imagePath
                    ]);
                    // $article->save();
                    return response()->json(['message' => 'modification d\'article enregistrée avec succès', 'article' => $article], 200);
                } catch (\Throwable $th) {
                    //throw $th;
                    return response()->json(['erreur' => 'probleme dans la modification d\' un article '], 408);
                }
            }
            return response()->json(['message' => 'article Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur modification article', $th], 408);
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
            $article = Article::where('isDeleted', false)->find($id);

            if (!$article) {
                return response()->json(['message' => 'Article non trouvée'], 404);
            }

            $article->isDeleted = true;
            $article->save();

            return response()->json(['message' => 'Article supprimée avec succès']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur lors de la suppression de l\'article', $th], 408);
        }
    }
}
