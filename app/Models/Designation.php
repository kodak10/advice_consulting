<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
        'reference', 
        'description', 
        'prix_unitaire', 
    ];
}
