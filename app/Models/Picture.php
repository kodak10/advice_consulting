<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Picture extends Model
{
    use HasFactory;
    use LogsActivity;

    // Les attributs qui sont attribuables massivement
    protected $fillable = [
        'user_id', 
        'group_id', 
        'image_path', 
        'image_profil_path',
        'caption'
    ];

    // Définition de la relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Définition de la relation avec le groupe (si nécessaire)
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    
    // Configuration de l'enregistrement des logs
    protected static $logAttributes = ['user_id', 'group_id', 'image_path', 'image_profil_path', 'caption'];
    protected static $logName = 'Pictures'; // Nom personnalisé pour le log
    protected static $logOnlyDirty = true; // Log uniquement les changements

    // Message personnalisé pour les actions
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'group_id', 'image_path', 'image_profil_path', 'caption']) // Spécifie les champs à suivre
            ->logOnlyDirty() // Journaliser uniquement les modifications
            ->setDescriptionForEvent(fn (string $eventName) => "Une action de type {$eventName} a été effectuée sur une image");
    }
}