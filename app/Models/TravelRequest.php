<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'travel_requests';

    protected $fillable = [
        'label',
        'date',
        'lieu',
        'du',
        'au',
        'motif',
        'montant_c',
        'en_lettre',
        'billet',
        'cheque',
        'hebergement',
        'espece',
        'total',
        'users_id',
        'direction_id',
        'filliale_id',
        'type_demandes_id',
        'statut'
    ];

    public function accords()
    {
        return $this->hasMany(notification_demande::class);
    }
    public function notification()
    {
        return $this->hasMany(notification_demande::class, 'travel_id')->with('user');
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
        return $this->belongsTo(User::class, 'users_id')->with('userProfile');
    }

    public function userprofile()
    {
        return $this->belongsTo(UserProfile::class,'users_id');
    }

    public function direction()
    {
        return $this->belongsTo(direction::class);
    }

    public function documents()
    {
        return $this->belongsTo(document::class, 'travel_id');
    }

    public function filliales()
    {
        return $this->belongsTo(filliale::class, 'filliale_id');
    }

    public function types()
    {
        return $this->belongsTo(type_demande::class, 'type_demandes_id');
    }
}
