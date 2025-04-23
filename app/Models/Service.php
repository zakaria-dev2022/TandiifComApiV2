<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'description',
        'prix',
        'image',
    ];

    //  Relation avec réservations 
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
