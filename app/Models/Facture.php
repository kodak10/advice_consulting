<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    // Les attributs qui peuvent être remplis via la méthode fill()
    protected $fillable = [
        'devis_id', // L'ID du devis
        'user_id',  // L'ID de l'utilisateur qui a créé la facture
        'num_bc',   // Le numéro de bon de commande
        'num_rap',  // Le numéro de rapport d'activité
        'num_bl',   // Le numéro du bon de livraison
    ];

    // La relation avec le modèle Devis (une facture appartient à un devis)
    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }
    public function details()
{
    return $this->hasMany(DevisDetail::class);  // Assure-toi que c'est bien un hasMany
}


    // La relation avec le modèle User (une facture appartient à un utilisateur)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
