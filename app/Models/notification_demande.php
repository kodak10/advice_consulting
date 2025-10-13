<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class notification_demande extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use LogsActivity;

    protected $fillable = [
        'user_id',
        'demande_id',
        'travel_id',
        'travel_request_id',
        'statut',
        'circuit_id',
        'organe_id',
        'order'
    ];

    public function demande()
    {
        return $this->belongsTo(demande::class)->with('types')->with('objetsg')->with('objets')->with('user')->with('userprofile')->with('filliales');
    }
    public function travel()
    {
        return $this->belongsTo(TravelRequest::class)->with('types')->with('user')->with('userprofile')->with('filliales');
    }
    public function user()
    {
        return $this->belongsTo(user::class);
    }

    // Configuration de l'enregistrement des logs
    protected static $logAttributes = ['user_id', 'demande_id', 'statut', 'circuit_id', 'organe_id', 'order'];
    protected static $logName = 'notification_demandes'; // Nom personnalisé pour le log
    protected static $logOnlyDirty = true; // Log uniquement les changements

    // Message personnalisé pour les actions
    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly(['user_id', 'demande_id', 'statut', 'circuit_id', 'organe_id', 'order']) // Champs suivis
    //         ->logOnlyDirty() // Journaliser uniquement les modifications
    //         ->setDescriptionForEvent(fn (string $eventName) => "Une action de type {$eventName} a été effectuée sur une notification de demande");
    // }
}
