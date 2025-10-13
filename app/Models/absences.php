<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class absences extends Model
{
    //
    use SoftDeletes;
    use HasFactory;
    // use LogsActivity;


    protected $fillable = [
        'detail',
        'lieu_travail',
        'motif_permi',
        'motif',
        'heure_debut',
        'heure_fin',
        'date_depart',
        'date_fin',
        'statut',
        'nombre_de_jours',
        'user_id',
        'direction_id',
        'filliale_id',
        'type_demandes_id'
    ];
}
