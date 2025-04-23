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
        'matricule',
        'profil' // Photo de profil
    ];

    // Génére automatiquement le matricule après création
    protected static function booted()
    {
        static::created(function ($emploie) {
            // Générer un matricule après création (ex: TC0001)
            $matricule = 'TC' . str_pad($emploie->id, 4, '0', STR_PAD_LEFT);

            // Mise à jour directe sans boucle infinie
            $emploie->update(['matricule' => $matricule]);
        });
    }





}
