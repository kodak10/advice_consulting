<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConfigurationGenerale extends Model
{
    use HasFactory;

    protected $table = 'configuration_generale';

    protected $fillable = [
        'nom',
        'logo',
        'contact',
        'ncc',
        'adresse',
        'email'
    ];
}
