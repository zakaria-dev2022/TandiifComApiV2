<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'commentaire',
    ];

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
