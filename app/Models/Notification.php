<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'type', 'notifiable_id', 'notifiable_type', 'data', 'read_at'];

    /**
     * Convertir le champ `data` en tableau automatiquement.
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
}
