<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeDemploi extends Model
{
    use HasFactory;
    protected $table = 'demandes_demplois';

    protected $fillable = [
        'nom_complet',
        'cin',
        'tel',
        'email',
        'copie_cin',
        'copie_permis',
        'adresse',
        'motivation',
        'profil' // Photo de profil
    ];
}
