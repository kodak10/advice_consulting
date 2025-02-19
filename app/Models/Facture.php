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
        'remise_speciale'
    ];

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
}
