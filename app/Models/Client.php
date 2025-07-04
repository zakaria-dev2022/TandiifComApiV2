<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_complet',
        // 'cin',
        'tel',
        'email',
        // 'adresse',
        'profil',
    ];
    // Relation 
    // Un client peut faire plusieurs réservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Un client peut écrire plusieurs commentaires
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }
}
