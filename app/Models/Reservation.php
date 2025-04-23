<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'service_id',
        'date_reservation',
        'adresse',
    ];

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
