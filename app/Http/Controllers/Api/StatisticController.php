<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AffectationReservation;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\Vehicule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
//     public function getStatistiques()
//     {
//         $today = Carbon::today();

//         // 1. Statistiques générales
//         $totalClients = Client::count();
//         $vehiculesActifs = Vehicule::where('status', 'Disponible')->count();
//         $reservationsToday = Reservation::whereDate('date_reservation', $today)->count();
//         $affectationsTermineesToday = AffectationReservation::where('status', 'Terminer')->count();

//         // 2. État des véhicules
//         // $etatVehicules = [
//         //     'Disponible' => Vehicule::where('status', 'Disponible')->count(),
//         //     'EnService' => Vehicule::where('status', 'En service')->count(),
//         //     'maintenance' => Vehicule::where('status', 'maintenance')->count(),
//         // ];
//         $etatVehicules = [
//     'Disponible' => Vehicule::where('status', 'Disponible')->where('isDeleted', 0)->count(),
//     'EnService' => Vehicule::where('status', 'En service')->where('isDeleted', 0)->count(),
//     'maintenance' => Vehicule::where('status', 'maintenance')->where('isDeleted', 0)->count(),
// ];


//         // 3. Réservations par mois (année en cours)
//         $reservationsParMois = Reservation::selectRaw('MONTH(date_reservation) as mois, COUNT(*) as total')
//             ->whereYear('date_reservation', $today->year)
//             ->groupBy('mois')
//             ->orderBy('mois')
//             ->get();

//         return response()->json([
//             'clients' => $totalClients,
//             'vehicules_actif' => $vehiculesActifs,
//             'reservations_today' => $reservationsToday,
//             'affectations_terminees_today' => $affectationsTermineesToday,
//             'etat_vehicules' => $etatVehicules,
//             'reservations_par_mois' => $reservationsParMois
//         ]);
//     }

public function getStatistiques()
{
    $today = Carbon::today();

    // 1. Statistiques générales
    $totalClients = Client::count();
    $vehiculesActifs = Vehicule::where('status', 'Disponible')->where('isDeleted', 0)->count();
    $reservationsToday = Reservation::whereDate('date_reservation', $today)->count();
    $affectationsTermineesToday = AffectationReservation::where('status', 'Terminer')->whereDate('created_at', $today)->count();

    // 2. État des véhicules
    $etatVehicules = [
        'Disponible' => Vehicule::where('status', 'Disponible')->where('isDeleted', 0)->count(),
        'EnService' => Vehicule::where('status', 'En service')->where('isDeleted', 0)->count(),
        'maintenance' => Vehicule::where('status', 'maintenance')->where('isDeleted', 0)->count(),
    ];

    // 3. Réservations par mois (année en cours)
    $reservationsParMois = Reservation::selectRaw('MONTH(date_reservation) as mois, COUNT(*) as total')
        ->whereYear('date_reservation', $today->year)
        ->groupBy('mois')
        ->orderBy('mois')
        ->get();

    // ✅ 4. Affectations terminées par mois
    $affectationsTermineesParMois = AffectationReservation::selectRaw('MONTH(date_affectation) as mois, COUNT(*) as total')
        ->where('isDeleted', 0)
        ->where('status', 'Terminer')
        ->whereYear('date_affectation', $today->year)
        ->groupBy('mois')
        ->orderBy('mois')
        ->get();

    return response()->json([
        'clients' => $totalClients,
        'vehicules_actif' => $vehiculesActifs,
        'reservations_today' => $reservationsToday,
        'affectations_terminees_today' => $affectationsTermineesToday,
        'etat_vehicules' => $etatVehicules,
        'reservations_par_mois' => $reservationsParMois,
        'affectations_terminees_par_mois' => $affectationsTermineesParMois, // ✅ Ajout
    ]);
}

}
