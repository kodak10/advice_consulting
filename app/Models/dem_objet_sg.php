<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class dem_objet_sg extends Model
{
    use SoftDeletes;
    use HasFactory;
    use LogsActivity;

    protected $fillable = 
    [
        'label',
        'dem_objet_g_id',
        'user_id'
    ];

    public function objets()
    {
        return $this->hasMany(dem_objet::class);
    }

    public function dem_vers_objets(): MorphMany
    {
        return $this->morphMany(dem_vers_objet::class, 'classe', 'dem_objets_id');
    }
    
    // Configuration de l'enregistrement des logs
    protected static $logAttributes = ['label', 'dem_objet_g_id', 'user_id'];
    protected static $logName = 'dem_objet_sgs'; // Nom personnalisé pour le log
    protected static $logOnlyDirty = true; // Log uniquement les changements

    // Message personnalisé pour les actions
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['label', 'dem_objet_g_id', 'user_id']) // Suivi des champs modifiés
            ->logOnlyDirty() // Journaliser uniquement les modifications
            ->setDescriptionForEvent(fn (string $eventName) => "Une action de type {$eventName} a été effectuée sur l'objet associé à dem_objet_sg");
    }
}