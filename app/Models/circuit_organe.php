<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class circuit_organe extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'label',
        'filliale_id',
    ];

    public function filliale()
    {
        return $this->belongsTo(filliale::class, 'filliale_id');
    }

    public function users()
    {
        return $this->hasMany(circuit_organe_user::class)->with('user');
    }

    public function direction()
    
    {
        return $this->hasOne(direction::class, 'id', 'label');
    }
    
    // Configuration de l'enregistrement des logs
    protected static $logAttributes = ['label', 'filliale_id'];
    protected static $logName = 'circuit_organes'; // Nom personnalisé pour le log
    protected static $logOnlyDirty = true; // Log uniquement les changements

    // Message personnalisé pour les actions
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['label', 'filliale_id']) // Suivi des champs modifiés
            ->logOnlyDirty() // Journaliser uniquement les modifications
            ->setDescriptionForEvent(fn (string $eventName) => "Une action de type {$eventName} a été effectuée sur circuit_organe");
    }
}