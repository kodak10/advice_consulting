<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class type_organe_validateur extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'order',
        'organe_validateur_id',
        'circuit_organe_id',
        'filliale_id'
    ];

    public function circuit()
    {
        return $this->belongsTo(circuit_organe::class, 'circuit_organe_id')->with('users', 'direction');
    }
    
    // Configuration de l'enregistrement des logs
    protected static $logAttributes = ['order', 'organe_validateur_id', 'circuit_organe_id', 'filliale_id'];
    protected static $logName = 'type_organe_validateurs'; // Nom personnalisé pour le log
    protected static $logOnlyDirty = true; // Log uniquement les changements

    // Message personnalisé pour les actions
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['order', 'organe_validateur_id', 'circuit_organe_id', 'filliale_id']) // Champs à suivre
            ->logOnlyDirty() // Ne journalise que les modifications
            ->setDescriptionForEvent(fn (string $eventName) => "Une action de type {$eventName} a été effectuée sur type_organe_validateur");
    }
}
