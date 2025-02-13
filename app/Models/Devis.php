<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    protected $fillable =[
        'user_id',
        'banque_id',
        'client_id',
        'date_emmision',
        'date_echeance',
        'num_proforma',
        'num_bc',
        'num_rap',
        'num_bl',
        'ref_designation',
        'description_designation',
        'qte_designation',
        'prixUnitaire_designation',
        'total_designation',
        'remise_speciale',
        'totall_ht',
        'tva',
        'total_ttc',
        'accompte',
        'solde',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function banque()
    {
        return $this->belongsTo(Banque::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
