<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class circuit_organe_user extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'order',
        'circuit_organe_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Configuration de l'enregistrement des logs
    protected static $logAttributes = ['order', 'circuit_organe_id', 'user_id'];
    protected static $logName = 'circuit_organe_users'; // Nom personnalisé pour le log
    protected static $logOnlyDirty = true; // Log uniquement les changements

    // Message personnalisé pour les actions
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['order', 'circuit_organe_id', 'user_id']) // Suivi des champs modifiés
            ->logOnlyDirty() // Journaliser uniquement les modifications
            ->setDescriptionForEvent(fn (string $eventName) => "Une action de type {$eventName} a été effectuée sur circuit_organe_user");
    }
}