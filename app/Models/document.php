<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class document extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'type',
        'nom',
        'user_id',
        'chemin_doc',
        'demande_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function demande()
    {
        return $this->belongsTo(demande::class, 'demande_id');
    }
    
    // Configuration de l'enregistrement des logs
    protected static $logAttributes = ['type', 'nom', 'user_id', 'chemin_doc', 'demande_id'];
    protected static $logName = 'documents'; // Nom personnalisé pour le log
    protected static $logOnlyDirty = true; // Log uniquement les changements

    // Message personnalisé pour les actions
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['type', 'nom', 'user_id', 'chemin_doc', 'demande_id']) // Suivi des champs modifiés
            ->logOnlyDirty() // Journaliser uniquement les modifications
            ->setDescriptionForEvent(fn (string $eventName) => "Une action de type {$eventName} a été effectuée sur un document");
    }
}