<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffectationCommande extends Model
{
    use HasFactory;
     protected $fillable = [
        'commande_id',
        'emploie_id',
        'vehicule_id',
        'date_affectation',
        'status',
        'commentaire_emploie',
    ];

    // Relations
    public function commande()
    {
        return $this->belongsTo(Commande::class);
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
