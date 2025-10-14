<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = ['facture_id', 'montant', 'user_id'];

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
