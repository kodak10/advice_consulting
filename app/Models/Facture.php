<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'devis_id', 
        'user_id',  
        'num_bc',   
        'num_rap',  
        'num_bl',
        'remise_speciale',
        'pays_id',
        'pdf_path',
        'status',
        'message',
        'type_facture',
        'montant',
        'net_a_payer'
    ];

    protected $casts = [
        'date_emission' => 'date',
        'date_echeance' => 'date',
        'selected_items' => 'array',
    ];
    public function getDateEmissionAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function getDateEcheanceAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }


    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }
    public function details()
    {
        return $this->hasMany(DevisDetail::class);  
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }
    public function creator()
    {
        return $this->devis->creator(); // Relation indirecte via Devis
    }

    


    public function getSelectedItemsAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    public function setSelectedItemsAttribute($value)
    {
        $this->attributes['selected_items'] = json_encode($value);
    }
}
