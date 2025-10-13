<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class missions extends Model
{
    use SoftDeletes;
    use HasFactory;
    // use LogsActivity;


    protected $fillable = [
        'montant_demande',
        'detail',
        'lieu_travail',
        'motif_permi',
        'motif',
        'payement',
        'date_depart',
        'date_fin',
        'type',
        'statut',
        'nombre_de_jours',
        'user_id',
        'direction_id',
        'procces_valide_result',
        'filliale_id',
        'type_demandes_id'
    ];
}
