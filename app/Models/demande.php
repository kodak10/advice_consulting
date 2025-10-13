<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class demande extends Model
{
    use SoftDeletes;
    use HasFactory;
    // use LogsActivity;


    protected $fillable = [
        'montant_demande',
        'detail',
        'lieu_travail',
        'motif_permi',
        'motif',
        'payement',
        'heure_debut',
        'heure_fin',
        'date_depart',
        'date_fin',
        'type',
        'statut',
        'nombre_de_jours',
        'user_id',
        'direction_id',
        'procces_valide_result',
        'filliale_id',
        'dem_vers_objets_id',
        'type_demandes_id'
    ];


    public function accords()
    {
        return $this->hasMany(notification_demande::class);
    }
    public function notification()
    {
        return $this->hasMany(notification_demande::class, 'demande_id')->with('user');
    }
    public function procces_valide()
    {
        try
        {
            // recuperation du circuit de la demande
            $circuit = circuit_organe::where('label', $this->direction_id)->with('users')->first();
            // recuperation de l'organe de la demande
            // dd($circuit);
            $organe = organe_validateur::where('label', $this->type_demandes_id)->with('types')->get()->pluck('types')->flatten();

            $circuit_organe = $organe->where('filliale_id', $this->filliale_id)->map(function($circuit_o, $key)
            {
                return
                [
                    'circuit' => $circuit_o->circuit,
                ];
            });

            $total_notification = notification_demande::where('demande_id', $this->id)->where('statut', 1)->count();

            $total_circuit_user = $circuit_organe
            ->sum(function($value)
            {
                return $value['circuit']->users->count();
            });

            $organe_circuit_users = $circuit_organe->where('filliale_id', $this->filliale_id)->map(function ($value)
            {
                return $value['circuit']->users;
            })->flatten();

            $all_users = isset($circuit) ? $circuit->users->concat($organe_circuit_users) : 0;

            $user_total =isset($circuit) ? $circuit->users->count() + $total_circuit_user : 0;


            return [
                'user_total' => $user_total,
                'notif_total' => $total_notification,
                'all_users' => $all_users,
            ];

        }catch (\Exception $e)
        {
            dd($e);
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->with('userProfile');
    }

    public function userprofile()
    {
        return $this->belongsTo(UserProfile::class,'user_id');
    }

    public function direction()
    {
        return $this->belongsTo(direction::class);
    }

    public function documents()
    {
        return $this->belongsTo(document::class, 'demande_id');
    }

    public function filliales()
    {
        return $this->belongsTo(filliale::class, 'filliale_id');
    }

    public function types()
    {
        return $this->belongsTo(type_demande::class, 'type_demandes_id');
    }

    // public function dem_objet()
    // {
    //     return $this->hasMany(dem::class, 'dem_objet_id');
    // }

    // public function objets()
    // {
    //     // 1. Divisez la chaîne d'IDs en un tableau
    //     $ids = explode(',', $this->dem_objets_id);

    //     // 2. Initialisez un tableau pour stocker les objets associés
    //     $objets = [];

    //     // 3. Parcourez les IDs
    //     foreach ($ids as $id) {
    //         // 4. Récupérez l'objet associé à l'ID
    //         $objet = dem_objet::find($id);

    //         // Si vous voulez utiliser findOrFail pour lever une exception si l'objet n'est pas trouvé
    //         // $objet = dem_objet::findOrFail($id);

    //         if ($objet) {
    //             $objets[] = $objet;
    //         }
    //     }

    //     return $objets;
    // }

    public function objets()
    {
        return $this->hasMany(dem_vers_objet::class, 'demandes_id')->with('objet');
    }



    public function objetsg()
    {
        return $this->hasManyThrough(dem_objet_sg::class, dem_vers_objet::class, 'demandes_id', 'id', 'id', 'dem_objets_id');
    }

    // Configuration de l'enregistrement des logs
    protected static $logAttributes = ['montant_demande', 'detail', 'motif', 'statut'];
    protected static $logName = 'demandes'; // Nom personnalisé pour le log
    protected static $logOnlyDirty = true; // Log uniquement les changements

    // Message personnalisé pour les actions
    
}
