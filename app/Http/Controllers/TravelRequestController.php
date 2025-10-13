<?php

namespace App\Http\Controllers;

use App\Models\TravelRequest;
use Illuminate\Http\Request;
use App\Models\document;
use App\Models\notification_demande;
use App\Models\type_organe_validateur;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\dem_objet_sg;
use App\Models\dem_objet_g;
use App\Models\dem_objet;
use App\Models\filliale;
use App\Models\circuit_organe;
use App\Models\organe_validateur;
use App\Models\dem_vers_objet;
use App\Models\demande;
use App\Models\type_demande;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\ImageManager;

class TravelRequestController extends Controller
{
    // Afficher la liste des demandes
    public function index(Request $request)
    {
        $travel  = TravelRequest::where('users_id', auth()->user()->id)->with('types')->with('documents')->with('notification')->orderBy('created_at', 'desc')->get();

        $demande_a_traiter = notification_demande::whereNotNull('travel_id')->where('statut', 0)->where('user_id', auth()->user()->id)->with('travel')->whereHas('travel', function ($query) {
            // Assuming demande has not been soft-deleted
            $query->whereNull('deleted_at');
        })->orderBy('created_at', 'desc')->get();

        $demande_traiter = notification_demande::whereNotNull('travel_id')
        ->where(function ($query) {
            $query->where('statut', 1)
                ->orWhere('statut', 2);
        })
        ->where('user_id', auth()->user()->id)
        ->with('travel')
        ->whereHas('travel', function ($query) {
            // Assuming demande has not been soft-deleted
            $query->whereNull('deleted_at');
        })
        ->orderBy('created_at', 'desc')
        ->get();

        $unprocessedDemandCount = notification_demande::whereNotNull('travel_id')
        ->where('statut', 0)
        ->where('user_id', auth()->user()->id)
        ->whereHas('travel', function ($query) {
            $query->whereNull('deleted_at');
        })
        ->count();
        $profiles = UserProfile::with('user')->with('directions')->orderBy('created_at', 'desc')->get();
        // notification_demande::whereNotNull('demande_id')->where('statut', 1)->whereHas('demande', function($query) {$query->where('user_id', auth()->user()->id);})->with('demande')->orderBy('created_at', 'desc')->get();
        // dd($demande_a_traiter);
        $user = User::with('userProfile')->find(auth()->user()->id);
        // $demande = UserProfile::with('directions_id')->find(auth()->user()->directions_id);


        if($request->ajax()){
            $response = [
            'travel' => $travel,
            'demande_a_traiter' => $demande_a_traiter,
            'travel_traiter' => $demande_traiter,
            'profiles' => $profiles,
            ];

            // Condition pour inclure ou non 'unprocessed_demand_count' dans la réponse
            if ($unprocessedDemandCount != 0) {
                $response['unprocessed_demand_count'] = $unprocessedDemandCount;
            } else {
                $response['unprocessed_demand_count'] = '';
            }

            return response()->json($response);
            // dd($request);
        }

        // Récupérer les demandes après application du filtrage
        // $travel = $query->get();
        return view('pages.travelrequest', ['travel'=>$travel, 'user'=>$user]);
    }

    public function indexdemtraiter(Request $request)
    {
        //
        $travel  = TravelRequest::where('users_id', auth()->user()->id)->with('types')->with('documents')->with('notification')->orderBy('created_at', 'desc')->get();
        // dd($demande, $request);

        $travel->map(function ($travel) {
            $travel->procces_valide_result = $travel->procces_valide();
            $travel->nombre_notifications = $travel->notification->count();
            // dd($demande->procces_valide_result);
            return $travel;
        });

        $demande_a_traiter = notification_demande::whereNotNull('travel_id')->where('statut', 0)->where('user_id', auth()->user()->id)->with('travel')->whereHas('travel', function ($query) {
            // Assuming demande has not been soft-deleted
            $query->whereNull('deleted_at');
        })->orderBy('created_at', 'desc')->get();

        $demande_traiter = filliale::with(['travel' => function($query) {
            $query->whereHas('notification', function($query) {
                $query->whereNotNull('travel_id')
                      ->where(function ($query) {
                          $query->where('statut', 1)
                                ->orWhere('statut', 2);
                      })
                      ->where('user_id', Auth::user()->id)
                      ->orderBy('created_at', 'desc');
            })
            ->whereNull('deleted_at');
        }])->get();

        $unprocessedDemandCount = notification_demande::whereNotNull('travel_id')
        ->where('statut', 0)
        ->where('user_id', auth()->user()->id)
        ->whereHas('travel', function ($query) {
            $query->whereNull('deleted_at');
        })
        ->count();
        // notification_demande::whereNotNull('demande_id')->where('statut', 1)->whereHas('demande', function($query) {$query->where('user_id', auth()->user()->id);})->with('demande')->orderBy('created_at', 'desc')->get();
        // dd($demande_a_traiter);
        $user = User::with('userProfile')->find(auth()->user()->id);
        // $demande = UserProfile::with('directions_id')->find(auth()->user()->directions_id);

        if($request->ajax()){
            $response = [
            'travel' => $travel,
            'demande_a_traiter' => $demande_a_traiter,
            'travel_traiter' => $demande_traiter,
            ];

            // Condition pour inclure ou non 'unprocessed_demand_count' dans la réponse
            if ($unprocessedDemandCount != 0) {
                $response['unprocessed_demand_count'] = $unprocessedDemandCount;
            } else {
                $response['unprocessed_demand_count'] = '';
            }

            return response()->json($response);
        }
        return view('pages.demande_traiter', ['travel_traiter'=>$demande_traiter, 'user'=>$user]);
    }

    // Afficher le formulaire de création
    public function create(Request $request)
    {
        $filliale = filliale::all();
        $profile = UserProfile::where('user_id', auth()->user()->id)->first();
        return view($request->view,
        [
            // response()->json(['date_fin' => $dateDepart + 30]),
            'filliales' => $filliale,
            'user' => $profile
        ]);
    }

    // Enregistrer une nouvelle demande
    public function store(Request $request)
    {
        //
        // dd($request);

        DB::beginTransaction();

        try
        {
            $user = User::with('userProfile')->find(auth()->user()->id);
            $request['users_id'] = auth()->user()->id;
            $request['direction_id'] = $user->userProfile->directions_id;
            $request['filliale_id'] = $user->userProfile->filliale_id;
            // $request['dem_objet_id'] = implode(',', $request['dem_objet_id']);

            // $request['dem_objet_id'] = $user->dem_objet_id;
                $validated = $request->validate
                ([
                    'label' => 'string|max:255',
                    'date' => 'date',
                    'lieu' => 'string|max:255',
                    'du' => 'date',
                    'au' => 'date|after_or_equal:du',
                    'motif' => 'string',
                    'montant_c' => 'numeric',
                    'en_lettre' => 'string',
                    'billet' => 'numeric',
                    'cheque' => 'numeric',
                    'hebergement' => 'numeric',
                    'espece' => 'numeric',
                    'total' => 'numeric',
                    'users_id'=>'integer',
                    'direction_id'=>'integer|required',
                    'filliale_id'=>'integer|required',
                    'type_demandes_id'=>'integer|required'
                ]);
                // dd($validated);
                // dd(is_object($demandesExistantes));
                $dem = TravelRequest::create($validated);

                // dd($dem_enregistre);
                //! envoie du mail de confirmation
                    # code...

                // dd($dem->id);
                // envoie de notification pour validation
                $this->notification($dem->id);

                DB::commit();
                // dd($dem);
                return response()-> json([
                    'error' => 0,
                    'message' => 'La demande a été enregistrée.'
                ]);

        } catch (\Exception $e)
        {
            dd($e);
            DB::rollBack();
            // Gestion de l'erreur
            if ($e->getPrevious()) {
                // code...
                $message=$e->getPrevious()->getMessage();
            } else {
                // code...
                $message=$e->getMessage();
            }

            return response()-> json([
                'error' => 1,
                'message' => $message,
            ]);
        }
    }

    public function notificationupdate(Request $request, $notification)
    {
        $notificationsArray = json_decode($notification);
        $results = [];

        foreach ($notificationsArray as $notificationId) {
            $notification = notification_demande::find($notificationId);

            if (!$notification) {
                $results[] = [
                    'id' => $notificationId,
                    'error' => 1,
                    'message' => 'Notification non trouvée.'
                ];
                continue;
            }

            $demande_recupere = TravelRequest::find($notification->travel_id);
            if (!$demande_recupere) {
                $results[] = [
                    'id' => $notification->id,
                    'error' => 1,
                    'message' => 'Demande non trouvée.'
                ];
                continue;
            }

            $circuit = circuit_organe::where('label', $demande_recupere->direction_id)
                ->where('filliale_id', $demande_recupere->filliale_id)
                ->with('users')
                ->first();

            $organe = organe_validateur::where('label', $demande_recupere->type_demandes_id)
                ->where('filliale_id', $demande_recupere->filliale_id)
                ->with('types')
                ->get()
                ->pluck('types')
                ->flatten();

            $circuit_organe = $organe->map(function($circuit_o) {
                return [
                    'circuit' => $circuit_o->circuit,
                ];
            });

            if ($demande_recupere->type_demandes_id == 1) {
                $demande_recupere->payement = $request->payement;
                $demande_recupere->save();
            }

            if ($request->statut == 2 ) {
                $demande_recupere->statut = 2;
                $demande_recupere->save();

                TravelRequest::where('id', $notification->travel_id)->update([
                    'motif' => $request->motif,
                ]);

                // 2. Mettre à jour toutes les notifications associées à cette demande
                notification_demande::where('travel_id', $demande_recupere->id)->update(['statut' => 2]); // Mettre toutes les notifications à "rejeté"

                $statut = $this->miseAJourStatutDemande($demande_recupere);

                // Appeler le contrôleur pour l'envoi d'un email de rejet
                (new SendEmailController())->NotificationValidate($statut, user::find($demande_recupere->user_id));

                // Sortir immédiatement pour éviter d'autres notifications
                return response()->json([
                    'error' => 0,
                    'message' => 'La demande a été rejetée avec succès.'
                ]);
            }
            if ($request->statut == 1) {
                // Mise à jour du statut et du motif lorsque statut = 1

                TravelRequest::where('id', $notification->travel_id)->update([
                    'motif' => $request->motif,
                ]);
            }

            if ($request->statut == 1) {
                $demandesExistantes = TravelRequest::where('type_demandes_id', 3)
                    ->where('direction_id', $demande_recupere->direction_id)
                    ->with(['accords' => function ($query) {
                        $query->where('statut', 1);
                    }])
                    ->get();

                if ($demande_recupere->type_demandes_id == 3) {
                    // code...
                    // Ajoutez cette ligne après la récupération de la direction de l'utilisateur
                    $demandesExistantes = TravelRequest::where('type_demandes_id', 3)
                        ->where('direction_id', $demande_recupere->direction_id)
                        ->where(function ($query) use ($demande_recupere) {
                            $query->whereBetween('date_depart', [$demande_recupere['date_depart'], $demande_recupere['date_fin']])
                                ->orWhereBetween('date_fin', [$demande_recupere['date_depart'], $demande_recupere['date_fin']])
                                ->orWhere(function ($query) use ($demande_recupere) {
                                    $query->where('date_depart', '<=', $demande_recupere['date_depart'])
                                    ->where('date_fin', '>=', $demande_recupere['date_fin']);
                                });
                            })
                            ->with(['accords' => function ($query) {
                            $query->where('statut', 1);
                        }])
                        ->get();

                    // Vérifier si $demandesExistantes est vide
                    if ($demandesExistantes->isEmpty()) {

                        foreach ($demandesExistantes as $demandeExistante) {
                            // dd($demandeExistante->accords->count());
                            if ($demandesExistante->accords->where('statut', 1)->count() > 0) {
                                return response()->json([
                                    'error' => 1,
                                    'message' => 'Vous ne pouvez pas valider cette demande car la période est déjà occupée par un autre congé.'
                                ]);
                            }
                        }
                    }
                }

                // if (count($demandesExistantes) > 0 && $demande_recupere->type_demandes_id == 3)
                // {
                //     foreach ($demandesExistantes as $demandesExistante) {
                //         $dateDebutNouvelle = Carbon::parse($demande_recupere->date_depart);
                //         $dateFinNouvelle = Carbon::parse($demande_recupere->date_fin);

                //         $dateDebutExistante = Carbon::parse($demandesExistante->date_depart);
                //         $dateFinExistante = Carbon::parse($demandesExistante->date_fin);

                //         // Vérification des chevauchements de dates
                //         $datesChevauchent = $this->chevauchementDates($dateDebutNouvelle, $dateFinNouvelle, $dateDebutExistante, $dateFinExistante);

                //         if ($datesChevauchent) {
                //             // Vérifie si la demande existante a des accords validés
                //             if ($demandesExistante->accords->where('statut', 1)->count() > 0) {
                //                 return response()->json([
                //                     'error' => 1,
                //                     'message' => 'Vous ne pouvez pas valider cette demande car la période est déjà occupée par un autre congé validé.'
                //                 ]);
                //             }
                //         }
                //     }
                // }

                $total_notification = notification_demande::where('travel_id', $demande_recupere->id)->count();

                $total_circuit_user = $circuit_organe
                    ->filter(function ($value) {
                        // Vérifiez si le circuit et la relation users existent
                        return isset($value['circuit']) && !empty($value['circuit']->users);
                    })
                    ->sum(function ($value) {
                        // Comptez les utilisateurs uniquement si la relation users est valide
                        return $value['circuit']->users->count();
                    });

                $total_user_validateur = ($circuit ? $circuit->users->count() : 0) + $total_circuit_user;

                if ($total_notification == $total_user_validateur) {
                    $demande_recupere->statut = 1;
                    $demande_recupere->save();

                    TravelRequest::where('id', $notification->travel_id)->update([
                        'motif' => $request->motif,
                    ]);

                    $statut = $this->miseAJourStatutDemande($demande_recupere);

                    (new SendEmailController())->NotificationValidate($statut, user::find($demande_recupere->user_id));

                    if ($demande_recupere->type_demandes_id == 3) {
                        $userProfile = UserProfile::find($demande_recupere->user_id);
                        $nouveauNombreDeJours = $userProfile->jour_de_conger - $demande_recupere->nombre_de_jours;
                        $userProfile->update(['jour_de_conger' => $nouveauNombreDeJours]);
                    }
                }

                $notification->update(['statut' => $request->statut]);

                $this->notification($notification->travel_id);

                $results[] = [
                    'id' => $notification->id,
                    'error' => 0,
                    'message' => 'La demande a bien été validée.'
                ];
            }
        }

        $errors = array_filter($results, function ($result) {
            return $result['error'] === 1;
        });

        if (!empty($errors)) {
            return response()->json($errors[0]);
        } else {
            return response()->json([
                'error' => 0,
                'message' => 'Toutes les demandes ont été validées avec succès.'
            ]);
        }
    }

    public function autonotifcationupdate($id)
    {

        // dd($demande_recupere);

        // Récupération de la demande
        $demande_recupere = TravelRequest::where('id', $id)->first();

        // Récupération du circuit de la demande
        $circuit = circuit_organe::where('label', $demande_recupere->direction_id)->where('filliale_id', $demande_recupere->filliale_id)->with('users')->first();

        // Récupération de l'organe de la demande
        $organe = organe_validateur::where('label', $demande_recupere->type_demandes_id)->where('filliale_id', $demande_recupere->filliale_id)->with('types')->get()->pluck('types')->flatten();

        $organe_id = organe_validateur::where('label', $demande_recupere->type_demandes_id)->with('types')->first()->label;

        $circuit_organe = $organe->map(function($circuit_o, $key)
        {
            return [
                'circuit' => $circuit_o->circuit,
            ];
        });
        if ($demande_recupere->type_demandes_id == 3) {
            // code...
            // Ajoutez cette ligne après la récupération de la direction de l'utilisateur
            $demandesExistantes = TravelRequest::where('type_demandes_id', 3)
                ->where('direction_id', $demande_recupere->direction_id)
                ->where(function ($query) use ($demande_recupere) {
                    $query->whereBetween('date_depart', [$demande_recupere['date_depart'], $demande_recupere['date_fin']])
                        ->orWhereBetween('date_fin', [$demande_recupere['date_depart'], $demande_recupere['date_fin']])
                        ->orWhere(function ($query) use ($demande_recupere) {
                            $query->where('date_depart', '<=', $demande_recupere['date_depart'])
                                ->where('date_fin', '>=', $demande_recupere['date_fin']);
                        });
                })
                ->with(['accords' => function ($query) {
                    $query->where('statut', 1);
                }])
                ->get();

            // Vérifier si $demandesExistantes est vide
            if ($demandesExistantes->isEmpty()) {

                foreach ($demandesExistantes as $demandeExistante) {
                    // dd($demandeExistante->accords->count());
                    if ($demandesExistante->accords->where('statut', 1)->count() > 0) {
                        return response()->json([
                            'error' => 1,
                            'message' => 'Vous ne pouvez pas valider cette demande car la période est déjà occupée par un autre congé.'
                        ]);
                    }
                }
            }
        }


        // dd($demande_recupere);
        $total_notification = notification_demande::where('travel_id', $demande_recupere->id)->count();

        $total_circuit_user = $circuit_organe
            ->filter(function ($value) {
                // Vérifiez si le circuit et la relation users existent
                return isset($value['circuit']) && !empty($value['circuit']->users);
            })
            ->sum(function ($value) {
                // Comptez les utilisateurs uniquement si la relation users est valide
                return $value['circuit']->users->count();
            });
        // dd($total_notification, $total_circuit_user, $circuit->users->count());

        $total_user_validateur = $circuit->users->count() + $total_circuit_user;


        if($total_notification === $total_user_validateur)
        {


            $demande_recupere->statut = 1;
            unset($demande_recupere['text_statut']);

            $demande_recupere->save();

            $statut = $this->miseAJourStatutDemande($demande_recupere);

            (new SendEmailController())->NotificationValidate($statut, user::find($demande_recupere->user_id));
            if ($demande_recupere->type_demandes_id == 3 && $demande_recupere->statut == 1)
            {
                $userProfile = UserProfile::find($demande_recupere->user_id);

                // Calculer le nouveau nombre de jours de congé
                $nouveauNombreDeJours = $userProfile->jour_de_conger - $demande_recupere->nombre_de_jours;

                // Mettre à jour la colonne jour_de_conger du modèle UserProfile
                $userProfile->update(['jour_de_conger' => $nouveauNombreDeJours]);
            }
        }

        // Ajouter le résultat à $results
        $results[] = [
            'id' => $demande_recupere->id,
            'error' => 0, // ou le statut d'erreur approprié
            'message' => 'La demande a bien été validée.'
        ];
    }

    public function analyseshow(Request $request, $notification)
    {
        //
        $notificationsArray = explode(',', $notification);

        $notif_demandes = notification_demande::whereIn('id', $notificationsArray)->get();

        $ids_demande_notif = $notif_demandes->pluck('travel_id');

        $ids_notif = $notif_demandes->pluck('id');

        // dd($request, is_array($notificationsArray),
        // $notificationsArray = explode(',', $notification));

        $demandes = TravelRequest::whereIn('id', $ids_demande_notif)->get();

        $ids_demande = $demandes->pluck('id');

        $types_demandeid = $demandes->pluck('type_demandes_id');

        // dd($ids_demande_notif, $notificationsArray, $demandes, $ids_demande, $types_demandeid);

        return view('modals.analyse_mission',
        [
            'id'=>$ids_notif,
            'demandes'=>$types_demandeid,
        ]);
    }

    public function notification($demande_enregistre_id)
    {
        // Étape 1 : Récupérer la demande
        $demande_recupere = TravelRequest::find($demande_enregistre_id);

        if (!$demande_recupere) {
            Log::error("Demande introuvable avec l'ID : $demande_enregistre_id");
            return;
        }

        // Étape 2 : Récupérer le circuit de validation
        $circuit = circuit_organe::where('label', $demande_recupere->direction_id)
            ->where('filliale_id', $demande_recupere->filliale_id)
            ->with('users')
            ->first();

        // Étape 3 : Récupérer les organes de validation
        $organe = organe_validateur::where('label', $demande_recupere->type_demandes_id)
            ->where('filliale_id', $demande_recupere->filliale_id)
            ->with('types')
            ->get()
            ->pluck('types')
            ->flatten();

        $circuit_organe = $organe->map(function ($circuit_o) {
            return [
                'circuit' => $circuit_o->circuit,
            ];
        });

        $usersorgane = [];

        // Étape 4 : Vérifier les notifications existantes
        $nb_notif_user = notification_demande::where('travel_id', $demande_recupere->id)->count();

        if ($demande_recupere->statut == 0) {
            // Ajout des utilisateurs du circuit principal
            if ($circuit && !empty($circuit->users)) {
                foreach ($circuit->users as $circuit_user_valideur) {
                    $usersorgane[] = [$circuit->label, $circuit_user_valideur];
                }
            }

            // Ajout des utilisateurs des organes de validation
            foreach ($circuit_organe as $circuit_valideur) {
                if (isset($circuit_valideur['circuit']) && !empty($circuit_valideur['circuit']->users)) {
                    foreach ($circuit_valideur['circuit']->users as $circuit_organe_user_valideur) {
                        $usersorgane[] = [$circuit_valideur['circuit']->label, $circuit_organe_user_valideur];
                    }
                }
            }

            // Retirer les utilisateurs déjà notifiés
            if ($nb_notif_user > 0) {
                $usersorgane = array_slice($usersorgane, $nb_notif_user);
            }

            $i = 0;
            $nombre_utilisateur_notif = count($usersorgane);

            // Notification des utilisateurs
            foreach ($usersorgane as $key => $direction_user) {
                Log::info('Notification envoyée : ' . $key . ' ' . $direction_user[1]->user->name);

                $userId = $direction_user[1]->user->id;

                $notificationData = [
                    'travel_id' => $demande_recupere->id,
                    'statut' => $demande_recupere->statut,
                    'user_id' => $userId,
                    'circuit_id' => $direction_user[0],
                    'order' => $direction_user[1]->order,
                ];

                notification_demande::create($notificationData);

                // Appeler la méthode pour envoyer un e-mail à l'utilisateur
                (new SendEmailController())->NotificationMission($demande_recupere, $direction_user[1]->user);


                // Si l'utilisateur n'est pas en auto-validation, sortir
                return;

                $i++;
            }

            // Étape 5 : Appeler autonotifcationupdate si toutes les notifications sont envoyées
            if ($i == $nombre_utilisateur_notif) {
                $this->autonotifcationupdate($demande_recupere->id);
            }
        }
    }

    private function miseAJourStatutDemande($demande)
    {
        if ($demande->statut == 0) {
            $demande['text_statut'] = 'En attente';
        } elseif ($demande->statut == 1) {
            $demande['text_statut'] = 'Validée';
        } else {
            $demande['text_statut'] = 'Rejetée';
        }

        return $demande;
    }

    // Afficher une demande spécifique
    public function show(TravelRequest $travelRequest)
    {
        return view('travel_requests.show', compact('travelRequest'));
    }

    // Afficher le formulaire de modification
    public function edit($id)
    {
        //
        $travel  = TravelRequest::where('id', $id)->with('types')->with('documents')->with('notification')->orderBy('created_at', 'desc')->first();
        // dd($Demande, $Demande->user->userProfile);
        if ($travel -> type_demandes_id == 1)
        {

            return view('edit.demande_bien_et_service',
            [
                'id' => $travel->id,
                'selectdemande'=> demande::where('id', $travel->id)->with('objets')->with('objetsg')->get(),
                // premiere recupere les elements de dem_objet_g et dem_objet
                'dem_objet_g'=> dem_objet_g::all(),
                'dem_objet'=> dem_objet_sg::with('objets')->get(),
                'montant_demande'=>$travel->montant_demande,
                'detail'=>$travel->detail
            ]);
        }
        if ($travel -> type_demandes_id == 2)
        {
            // dd($Demande->direction);
            return view('edit.demande_permission',
            [
                'id' => $travel->id,
                'selectdemande'=> demande::where('id', $travel->id)->with('objets')->get(),
                // premiere recupere les elements de dem_objet_g et dem_objet
                'dem_objet'=> dem_objet::all(),
                // 'dem_objet'=> dem_objet_sg::with('objets')->get(),
                // 'selectfilliales'=> demande::where('id', $Demande->id)->with('filliales')->get(),
                'filliales' => filliale::all(),
                'motif_permi'=> $travel->motif_permi,
                'user'=> $travel->user->userProfile,
                'date_depart' => $travel->date_depart,
                'date_fin' => $travel->date_fin
            ]);
        }
        if ($travel -> type_demandes_id == 3)
        {

            return view('edit.demande_conge',
            [
                'id' => $travel->id,
                'selectdemande'=> demande::where('id', $travel->id)->with('objets')->get(),
                // premiere recupere les elements de dem_objet_g et dem_objet
                'dem_objet'=> dem_objet::all(),
                // 'dem_objet'=> dem_objet_sg::with('objets')->get(),
                'selectfilliale'=> demande::where('id', $travel->id)->with('filliales')->get(),
                'user'=> $travel->user->userProfile,
                'nombre_de_jours' => $travel->nombre_de_jours,
                'date_depart' => $travel->date_depart,
                'date_fin' => $travel->date_fin
            ]);
        }
        if ($travel -> type_demandes_id == 5)
        {
            // dd(demande::where('id', $Demande->id)->with('objets')->get());
            return view('edit.demande_absence',
            [
                'id' => $travel->id,
                'selectdemande'=> demande::where('id', $travel->id)->with('objets')->get(),
                // premiere recupere les elements de dem_objet_g et dem_objet
                'dem_objet'=> dem_objet::all(),
                // 'dem_objet'=> dem_objet_sg::with('objets')->get(),
                // 'selectfilliales'=> demande::where('id', $Demande->id)->with('filliales')->get(),
                'filliales' => filliale::all(),
                'motif_permi'=> $travel->motif_permi,
                'user'=> $travel->user->userProfile,
                'date_depart' => $travel->date_depart,
                'date_fin' => $travel->date_fin
            ]);
        };
        // return response()->json([
        //    "idDemande"=>$id,
        // ]);
    }

    public function imprime($id)
    {
        $travel  = TravelRequest::where('id', $id)->with('types')->with('documents')->with('notification')->orderBy('created_at', 'desc')->first();

        return view('imprime.imprimer',
            [
                'id' => $travel->id,
                'label' => $travel->label,
                'date' => $travel->date,
                'lieu' => $travel->lieu,
                'du' => $travel->du,
                'au' => $travel->au,
                'motif' => $travel->motif,
                'montant_c' => $travel->montant_c,
                'en_lettre' => $travel->en_lettre,
                'billet' => $travel->billet,
                'cheque' => $travel->cheque,
                'hebergement' => $travel->hebergement,
                'espece' => $travel->espece,
                'total' => $travel->total
            ]);
    }

    public function imprimerdoc()
    {
        $travel  = TravelRequest::with('types')->with('documents')->with('notification')->orderBy('created_at', 'desc')->first();


        $data=[
            'id' => $travel->id,
            'label' => $travel->label,
            'date' => $travel->date,
            'lieu' => $travel->lieu,
            'du' => $travel->du,
            'au' => $travel->au,
            'motif' => $travel->motif,
            'montant_c' => $travel->montant_c,
            'en_lettre' => $travel->en_lettre,
            'billet' => $travel->billet,
            'cheque' => $travel->cheque,
            'hebergement' => $travel->hebergement,
            'espece' => $travel->espece,
            'total' => $travel->total
        ];

            $pdf = Pdf::loadView('pdf.travel_request', $data);

            return $pdf->stream('impression.pdf');
    }

    // Mettre à jour une demande
    public function update(Request $request, TravelRequest $travelRequest)
    {

       $request->validate([
            'label' => 'required|string|max:255',
            'date' => 'required|date',
            'lieu' => 'required|string|max:255',
            'du' => 'required|date',
            'au' => 'required|date|after_or_equal:du',
            'motif' => 'required|string',
            'montant_c' => 'required|numeric',
            'en_lettre' => 'required|string',
            'billet' => 'required|numeric',
            'cheque' => 'required|numeric',
            'hebergement' => 'required|numeric',
            'espece' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $travelRequest->update($request->all());

        return redirect()->route('travel_requests.index')->with('success', 'Demande mise à jour avec succès.');
    }

    // Supprimer une demande (soft delete)
    public function destroy(TravelRequest $travelRequest)
    {
        $travelRequest->delete();

        return redirect()->route('travel_requests.index')->with('success', 'Demande supprimée avec succès.');
    }
}
