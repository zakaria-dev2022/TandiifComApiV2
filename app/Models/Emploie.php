<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emploie extends Model
{
    use HasFactory;
    protected $table = 'emploies';

    protected $fillable = [
        'nom_complet',
        'cin',
        'tel',
        'email',
        'copie_cin',
        'copie_permis',
        'adresse',
        'profil' // Photo de profil
    ];
}
