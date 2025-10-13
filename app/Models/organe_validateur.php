<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class organe_validateur extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'label',
        'filliale_id',
    ];

    public function types()
    {
        return $this->hasMany(type_organe_validateur::class)->with('circuit');
    }

    public function type_demande()
    
    {
        return $this->hasOne(type_demande::class, 'id', 'label');
    }

    public function filliales()
    {
        return $this->belongsTo(filliale::class, 'filliale_id');
    }
    
    // Configuration de l'enregistrement des logs
    protected static $logAttributes = ['label', 'filliale_id'];
    protected static $logName = 'organe_validateurs'; // Nom personnalisé pour le log
    protected static $logOnlyDirty = true; // Log uniquement les changements

    // Message personnalisé pour les actions
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['label', 'filliale_id']) // Spécifie les champs à suivre
            ->logOnlyDirty() // Journaliser uniquement les modifications
            ->setDescriptionForEvent(fn (string $eventName) => "Une action de type {$eventName} a été effectuée sur l'organe validateur");
    }
}