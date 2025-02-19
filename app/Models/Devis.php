<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
  
    protected $fillable = [
        'client_id', 'date_emission', 'date_echeance', 
        'commande', 'livraison', 'validite', 'delai', 
        'banque_id', 'total_ht', 'tva', 'total_ttc', 
        'acompte', 'solde', 'status', 'num_proforma', 'pdf_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function banque()
    {
        return $this->belongsTo(Banque::class);
    }

    public function details()
    {
        return $this->hasMany(DevisDetail::class);
    }

    public function designations()
    {
        return $this->belongsToMany(Designation::class, 'devis_designation');
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }


}
