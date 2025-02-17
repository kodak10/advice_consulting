<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DevisDetail extends Model
{
    use HasFactory;

    protected $fillable = ['devis_id', 'designation_id', 'quantite', 'prix_unitaire', 'remise', 'total'];

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
}
