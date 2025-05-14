<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vehicule extends Model
{
    use HasFactory;
     protected $fillable = ['marque', 'type_id', 'matricule', 'status', 'image'];

    public function type()
    {
        return $this->belongsTo(Type_vehicule::class, 'type_id');
    }
}
