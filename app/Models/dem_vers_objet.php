<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class dem_vers_objet extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'dem_objets_id',
        'demandes_id',
        'classe',
    ];

    public function objetlabel()
    {
        return $this->belongsTo(dem_objet::class, 'id', 'dem_objets_id');
    }

    public function objet(): MorphTo
    {
        return $this->morphTo('classes', 'classe', 'dem_objets_id');
    }
    
    // Configuration de l'enregistrement des logs
    protected static $logAttributes = ['dem_objets_id', 'demandes_id', 'classe'];
    protected static $logName = 'dem_vers_objets'; // Nom personnalisé pour le log
    protected static $logOnlyDirty = true; // Log uniquement les changements

    // Message personnalisé pour les actions
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['dem_objets_id', 'demandes_id', 'classe']) // Suivi des champs modifiés
            ->logOnlyDirty() // Journaliser uniquement les modifications
            ->setDescriptionForEvent(fn (string $eventName) => "Une action de type {$eventName} a été effectuée sur l'objet associé à la demande");
    }
}