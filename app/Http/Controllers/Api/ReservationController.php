<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $reservation = Reservation::where('isDeleted', false)->get();
            return response()->json($reservation, 200);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(["message" => "erreur index reservation", $th], 408);
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
                    'service_id' => 'required|exists:services,id',
                    'date_reservation' => 'required|date',
                    'adresse' => 'required|string|max:255',
                    'heure' => 'required|date_format:H:i',
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
            }

            try {
                $reservation = Reservation::create([
                    'client_id' => $request->client_id,
                    'service_id' => $request->service_id,
                    'date_reservation' =>$request->date_reservation,
                    'adresse' => $request->adresse,
                    'heure' => $request->heure

                ]);
                return response()->json(['message' => 'nouveau reservation enregistrée avec succès', 'reservation' => $reservation], 201);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'probleme de creation d\'une reservation', $th], 408);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur Store Reservation", $th], 408);
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
            $reservation = Reservation::where('isDeleted', false)->find($id);

            if ($reservation) {
                return response()->json(['reservation' => $reservation], 200);
            }
            return response()->json(['message' => 'reservation Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Erreur show reservation", $th], 408);
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
                    'service_id' => 'required|exists:services,id',
                    'date_reservation' => 'required|date',
                    'adresse' => 'required|string|max:255',
                    'heure' => 'required|date_format:H:i',
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['erreur' => 'l\'un des conditions non valide '], 408);
            }

            $reservation = Reservation::where('isDeleted', false)->find($id);
            if ($reservation) {
                try {
                    $reservation->update([
                       'client_id' => $request->client_id,
                    'service_id' => $request->service_id,
                    'date_reservation' =>$request->date_reservation,
                    'adresse' => $request->adresse,
                    'heure' => $request->heure
                    ]);
                    return response()->json(['message' => 'modification d\'reservation enregistrée avec succès', 'reservation' => $reservation], 200);
                } catch (\Throwable $th) {
                    //throw $th;
                    return response()->json(['erreur' => 'probleme dans la modification d\' un reservation '], 408);
                }
            }
            return response()->json(['message' => 'reservation Introuvable!'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur modification reservation', $th], 408);
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
            $reservation = Reservation::where('isDeleted', false)->find($id);

            if (!$reservation) {
                return response()->json(['message' => 'Reservation non trouvée'], 404);
            }

            $reservation->isDeleted = true;
            $reservation->save();

            return response()->json(['message' => 'Reservation supprimée avec succès']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Erreur lors de la suppression de l\'reservation', $th], 408);
        }
    }
}
