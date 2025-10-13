<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
  
    use HasFactory;

    protected $fillable = [
        'client_id', 'date_emission', 'date_echeance', 
        'commande', 'livraison', 'validite', 'delai', 
        'banque_id', 'total_ht', 'tva', 'total_ttc', 
        'acompte', 'solde', 'status', 'num_proforma', 'pdf_path', 'pays_id', 'devise', 'taux', 'message', 'texte'
    ];

    protected $casts = [
        'date_emission' => 'date',
        'date_echeance' => 'date',
    ];
    public function getDateEmissionFrAttribute()
    {
        return $this->date_emission?->format('d-m-Y');
    }

    public function getDateEcheanceFrAttribute()
    {
        return $this->date_echeance?->format('d-m-Y');
    }

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
        return $this->hasMany(DevisDetail::class, 'devis_id');
    }

    public function designations()
    {
        return $this->belongsToMany(Designation::class, 'devis_designation');
    }

    public function facture()
    {
        return $this->hasOne (Facture::class);
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }


}
