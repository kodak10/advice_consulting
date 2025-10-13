<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class dem_objet_g extends Model
{
    use SoftDeletes;
    use HasFactory;
    use LogsActivity;

    protected $fillable = 
    [
        'label',
        'user_id'
    ];

    public function dem_objet_sgs()
    {
        return $this->hasMany(dem_objet_sg::class)->with('objets');
    }
    
    // Configuration de l'enregistrement des logs
    protected static $logAttributes = ['label', 'user_id'];
    protected static $logName = 'dem_objet_gs'; // Nom personnalisé pour le log
    protected static $logOnlyDirty = true; // Log uniquement les changements

    // Message personnalisé pour les actions
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['label', 'user_id']) // Suivi des champs modifiés
            ->logOnlyDirty() // Journaliser uniquement les modifications
            ->setDescriptionForEvent(fn (string $eventName) => "Une action de type {$eventName} a été effectuée sur l'objet associé à dem_objet_g");
    }
}