<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class filliale extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'UserProfile_id'
    ];

    public function circuit()
    {
        return $this->hasMany(circuit_organe::class)->with('direction', 'users');
    }

    public function organes()
    {
        return $this->hasMany(organe_validateur::class)->with('types', 'type_demande');
    }

    public function demande()
    {
        return $this->hasMany(demande::class)->with('accords', 'notification', 'user', 'userprofile', 'direction', 'documents', 'types', 'objets', 'objetsg');
    }

    public function travel()
    {
        return $this->hasMany(TravelRequest::class)->with('accords', 'notification', 'user', 'userprofile', 'direction', 'documents', 'types');
    }
}
