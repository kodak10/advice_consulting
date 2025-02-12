<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'nom', 
        'numero_cc', 
        'telephone', 
        'adresse', 
        'ville', 
        'attn', 
        'created_by',
    ];
}
