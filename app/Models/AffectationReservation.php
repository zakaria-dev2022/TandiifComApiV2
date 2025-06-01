<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffectationReservation extends Model
{
    use HasFactory;
      protected $fillable = [
        'reservation_id',
        'emploie_id',
        'vehicule_id',
        'date_affectation',
        'status',
        'commentaire_emploie',
    ];

    // Relations
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function employe()
    {
        return $this->belongsTo(Emploie::class, 'emploie_id');
    }

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }
}
