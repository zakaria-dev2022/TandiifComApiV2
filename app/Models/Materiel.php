<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type_materiel_id',
        'description',
        'image',
        'qte',
    ];

    public function typeMateriel()
    {
        return $this->belongsTo(Type_materiel::class, 'type_materiel_id');
    }
}
