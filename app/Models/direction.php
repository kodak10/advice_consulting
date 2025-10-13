<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class direction extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'label'
    ];
    
    // Configuration de l'enregistrement des logs
    protected static $logAttributes = ['label'];
    protected static $logName = 'directions'; // Nom personnalisé pour le log
    protected static $logOnlyDirty = true; // Log uniquement les changements

    // Message personnalisé pour les actions
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['label']) // Suivi des champs modifiés
            ->logOnlyDirty() // Journaliser uniquement les modifications
            ->setDescriptionForEvent(fn (string $eventName) => "Une action de type {$eventName} a été effectuée sur la direction");
    }
}