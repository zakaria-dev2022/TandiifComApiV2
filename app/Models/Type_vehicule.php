<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type_vehicule extends Model
{
    use HasFactory;
    protected $fillable = ['nom'];

    public function vehicules()
    {
        return $this->hasMany(vehicule::class, 'type_id');
    }
}
