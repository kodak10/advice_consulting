<?php

namespace App\Http\Controllers;

use App\Models\conger;
use App\Models\Document;
use App\Helpers\ImageManager;
use App\Models\Notification;
use App\Models\notification_demande;
use App\Models\circuit_organe;
use App\Models\organe_validateur;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CongerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $conger = conger::orderBy('motif', 'asc')->get();
        return response()->json($conger);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'motif_permi' => 'string|nullable',
            'motif' => 'string|nullable',
            'lieu_travail' => 'string|nullable',
            'heure_debut' => 'string|nullable',
            'heure_fin' => 'string|nullable',
            'date_depart' => 'date|nullable',
            'date_fin' => 'date|nullable',
            'nombre_de_jours'=>'integer|nullable',
            'user_id'=>'integer|nullable',
            'direction_id'=>'integer|nullable',
            'filliale_id'=>'integer|nullable',
        ]);

        $conger = conger::create([
            'motif_permi' => $validated['motif_permi'] ?? null,
            'motif' => $validated['motif'] ?? null,
            'lieu_travail' => $validated['lieu_travail'] ?? null,
            'heure_debut' => $validated['heure_debut'] ?? null,
            'heure_fin' => $validated['heure_fin'] ?? null,
            'date_depart' => $validated['date_depart'] ?? null,
            'date_fin' => $validated['date_fin'] ?? null,
            'nombre_de_jours' => $validated['nombre_de_jours'] ?? null,
            'user_id' => 1,
            'direction_id' => $validated['direction_id'] ?? null,
            'filliale_id' => $validated['filliale_id'] ?? null,
            'created_by' => auth()->id() ?? 1,
        ]);

                // dd($validated);
        // ...
        // $demandesExistantes ="";
        // if ($request->type_demandes_id==3) {
        //     # code...
        //     // Ajoutez cette ligne après la récupération de la direction de l'utilisateur
        //     $demandesExistantes = Demande::where('type_demandes_id', 3) // Assurez-vous que 3 est l'ID du type de demande de congé
        //     ->where('statut', 1)
        //     ->where('direction_id', $user->userProfile->directions_id)
        //     ->get();

        //     $request['motif_permi']="CONGES";
        // }

        // // dd($validated);
        // if (is_object($demandesExistantes) && $demandesExistantes->count() > 0) {
        //     foreach ($demandesExistantes as $key => $demandesExistante) {
        //         // code...
        //         $checkDate1 = Carbon::parse($request->date_depart); // Replace with your actual date
        //         $checkDate2 = Carbon::parse($request->date_fin); // Replace with your actual date

        //         $startDate = Carbon::parse($demandesExistante->date_depart);
        //         $endDate = Carbon::parse($demandesExistante->date_fin);

        //         $isWithinRange1 = $checkDate1->between($startDate, $endDate);
        //         $isWithinRange2 = $checkDate2->between($startDate, $endDate);

        //         if ($isWithinRange1 && $isWithinRange2) {
        //             if ($demandesExistante->accords->count() > 0) {
        //             // Si des demandes de congé existent dans la même direction et la même plage de dates, bloquez la nouvelle demande
        //                 return response()->json([
        //                     'error' => 1,
        //                     'message' => 'La periode demandée est déja occupée par un autre congé.'
        //                 ]);
        //             }
        //         }
        //         // else {
        //         //   echo "The date $checkDate falls outside the range [$startDate, $endDate]";
        //         // }
        //     }
        // }
        // dd(is_object($demandesExistantes));


        // // enregistrement des objets

        // if(is_array($request['dem_objet_id'])  && !empty($request['dem_objet_id']))
        // {
        //     foreach($request['dem_objet_id'] as $objetselect)
        //     {
        //         dem_vers_objet::create([
        //             'dem_objets_id' => $objetselect,
        //             'demandes_id' => $dem->id,
        //             'classe' => $dem->type_demandes_id == 1? dem_objet_sg::class : dem_objet::class,
        //         ]);
        //     }
        // // $collection = $dem->objets->implode('label', ', ');
        // // dd($collection, $dem->objets);
        // }

        // else
        // {
        //     dem_vers_objet::create([
        //         'dem_objets_id' => $request['dem_objet_id'],
        //         'demandes_id' => $dem->id,
        //         'classe' => $dem->type_demandes_id == 1? dem_objet_sg::class : dem_objet::class,
        //     ]);
        // }
        // $dem_enregistre = demande::where('id', $dem->id, $dem->objet)->with('user', 'direction', 'types', 'objets')->first();

        //enregistrement de document
        $path = 'Documents/';
        // Vérifier s'il existe des fichiers
        if ($request->files->count() > 0 )
        {
            $files = $request->files;

            foreach ($files as $file) {
                // Boucler sur les fichiers
                $fileData = ImageManager::uploads($file, $path);

                // Créer un nouveau document
                $document = Document::create([
                    'type' => 'justificatif',
                    'nom' => 'justificatif_demande_' . $conger->id.'_'.now(),
                    'chemin_doc' => $fileData['filePath'],
                    'user_id' => 1,
                    'demande_id' => $conger->id,
                ]);
            }

        }

        // dd($dem_enregistre);
        //! envoie du mail de confirmation
            # code...

        // dd($dem->id);
        // envoie de notification pour validation
        // $this->notification($dem->id);

        return response()->json(['message' => 'demande créé avec succès']);
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

            $demande_recupere = conger::find($notification->demande_id);
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

                conger::where('id', $notification->demande_id)->update([
                    'motif' => $request->motif,
                ]);

                // 2. Mettre à jour toutes les notifications associées à cette demande
                notification_demande::where('demande_id', $demande_recupere->id)->update(['statut' => 2]); // Mettre toutes les notifications à "rejeté"

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

                conger::where('id', $notification->demande_id)->update([
                    'motif' => $request->motif,
                ]);
            }

            if ($request->statut == 1) {
                $demandesExistantes = conger::where('type_demandes_id', 3)
                    ->where('direction_id', $demande_recupere->direction_id)
                    ->with(['accords' => function ($query) {
                        $query->where('statut', 1);
                    }])
                    ->get();

                if ($demande_recupere->type_demandes_id == 3) {
                    // code...
                    // Ajoutez cette ligne après la récupération de la direction de l'utilisateur
                    $demandesExistantes = conger::where('type_demandes_id', 3)
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
                            // if ($demandesExistante->accords->where('statut', 1)->count() > 0) {
                            //     return response()->json([
                            //         'error' => 1,
                            //         'message' => 'Vous ne pouvez pas valider cette demande car la période est déjà occupée par un autre congé.'
                            //     ]);
                            // }
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

                $total_notification = notification_demande::where('demande_id', $demande_recupere->id)->count();

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

                    conger::where('id', $notification->demande_id)->update([
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

                $this->notification($notification->demande_id);

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
        $demande_recupere = conger::where('id', $id)->first();

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
            $demandesExistantes = conger::where('type_demandes_id', 3)
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
                    // if ($demandesExistante->accords->where('statut', 1)->count() > 0) {
                    //     return response()->json([
                    //         'error' => 1,
                    //         'message' => 'Vous ne pouvez pas valider cette demande car la période est déjà occupée par un autre congé.'
                    //     ]);
                    // }
                }
            }
        }


        // dd($demande_recupere);
        $total_notification = notification_demande::where('demande_id', $demande_recupere->id)->count();

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

        $ids_demande_notif = $notif_demandes->pluck('demande_id');

        $ids_notif = $notif_demandes->pluck('id');

        // dd($request, is_array($notificationsArray),
        // $notificationsArray = explode(',', $notification));

        $demandes = conger::whereIn('id', $ids_demande_notif)->get();

        $ids_demande = $demandes->pluck('id');

        $types_demandeid = $demandes->pluck('type_demandes_id');

        // dd($ids_demande_notif, $notificationsArray, $demandes, $ids_demande, $types_demandeid);

        return view('modals.analyse_demande',
        [
            'id'=>$ids_notif,
            'demandes'=>$types_demandeid,
        ]);
    }

    public function notification($demande_enregistre_id)
    {
        // Étape 1 : Récupérer la demande
        $demande_recupere = conger::find($demande_enregistre_id);

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
        $nb_notif_user = notification_demande::where('demande_id', $demande_recupere->id)->count();

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
                    'demande_id' => $demande_recupere->id,
                    'statut' => $demande_recupere->statut,
                    'user_id' => $userId,
                    'circuit_id' => $direction_user[0],
                    'order' => $direction_user[1]->order,
                ];

                notification_demande::create($notificationData);

                // Appeler la méthode pour envoyer un e-mail à l'utilisateur
                (new SendEmailController())->NotificationMail($demande_recupere, $direction_user[1]->user);


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

    /**
     * Display the specified resource.
     */
    public function show(conger $conger)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(conger $conger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, conger $conger)
    {
        $conger = conger::findOrFail($conger);

        $user = User::with('userProfile')->find(1);
        // $request['directions_id'] = $user->userProfile->directions_id;
        $validated  = $request->validate([
            'objet' => 'string|nullable',
            'montant_demande' => 'integer|nullable',
            'motif_permi' => 'string|nullable',
            'motif' => 'string|nullable',
            'detail' => 'string|nullable',
            'payement' => 'integer|nullable',
            'lieu_travail' => 'string|nullable',
            'moment' => 'string|nullable',
            'periode' => 'string|nullable',
            'nombre_de_jours'=>'integer|nullable',
            'date_depart' => 'date|nullable',
            'date_fin' => 'date|nullable',
            'type' => 'string|nullable',
            'directions_id'=>'integer|nullable',
            'type_demandes_id'=>'integer|nullable'
        ]);

        // dd($Demande);

        // $Demande->update($validated);

        // dem_vers_objet::where('demandes_id', $Demande->id)->delete();

        // if(is_array($request['dem_objet_id'])  && !empty($request['dem_objet_id']))
        // {
        //     foreach($request['dem_objet_id'] as $objetselect)
        //     {
        //         dem_vers_objet::create([
        //             'dem_objets_id' => $objetselect,
        //             'demandes_id' => $Demande->id
        //         ]);
        //     }
        // }

        // else
        // {
        //     dem_vers_objet::create([
        //         'dem_objets_id' => $request['dem_objet_id'],
        //         'demandes_id' => $Demande->id
        //     ]);
        // }

        // $demandesExistantes ="";
        // if ($request->type_demandes_id==1) {
        //     # code...
        //     // Ajoutez cette ligne après la récupération de la direction de l'utilisateur
        //     $demandesExistantes = Demande::where('type_demandes_id', 3) // Assurez-vous que 3 est l'ID du type de demande de congé
        //     ->where('statut', 1)
        //     ->where('direction_id', $user->userProfile->directions_id)
        //     ->get();

        //     $request['motif_permi']="CONGES";
        // }

        // // dd($validated);
        // if (is_object($demandesExistantes) && $demandesExistantes->count() > 0) {
        //     foreach ($demandesExistantes as $key => $demandesExistante) {
        //         // code...
        //         $checkDate1 = Carbon::parse($request->date_depart); // Replace with your actual date
        //         $checkDate2 = Carbon::parse($request->date_fin); // Replace with your actual date

        //         $startDate = Carbon::parse($demandesExistante->date_depart);
        //         $endDate = Carbon::parse($demandesExistante->date_fin);

        //         $isWithinRange1 = $checkDate1->between($startDate, $endDate);
        //         $isWithinRange2 = $checkDate2->between($startDate, $endDate);

        //         if ($isWithinRange1 && $isWithinRange2) {
        //             if ($demandesExistante->accords->count() > 0) {
        //             // Si des demandes de congé existent dans la même direction et la même plage de dates, bloquez la nouvelle demande
        //                 return response()->json([
        //                     'error' => 1,
        //                     'message' => 'La periode demandée est déja occupée par un autre congé.'
        //                 ]);
        //             }
        //         }
        //         // else {
        //         //   echo "The date $checkDate falls outside the range [$startDate, $endDate]";
        //         // }
        //     }
        // }
        // // dd(is_object($demandesExistantes));
        // // $Demande = demande::update($validated);
        // Demande::where('id', $Demande)->update($request->all());

        // // enregistrement des objets

        // if(is_array($request['dem_objet_id'])  && !empty($request['dem_objet_id']))
        // {
        //     foreach($request['dem_objet_id'] as $objetselect)
        //     {
        //         dem_vers_objet::update([
        //             'dem_objets_id' => $objetselect,
        //             'demandes_id' => $Demande->id,
        //             'classe' => $Demande->type_demandes_id == 1? dem_objet_sg::class : dem_objet::class,
        //         ]);
        //     }
        // // $collection = $dem->objets->implode('label', ', ');
        // // dd($collection, $dem->objets);
        // }

        // else
        // {
        //     dem_vers_objet::update([
        //         'dem_objets_id' => $request['dem_objet_id'],
        //         'demandes_id' => $Demande->id,
        //         'classe' => $Demande->type_demandes_id == 1? dem_objet_sg::class : dem_objet::class,
        //     ]);
        // }
        // $dem_enregistre = demande::where('id', $Demande->id, $Demande->objet)->with('user', 'direction', 'types', 'objets')->first();

        //enregistrement de document
        $path = 'Documents/';
        // Vérifier s'il existe des fichiers
        if ($request->files->count() > 0 )
        {
            $files = $request->files;

            foreach ($files as $file) {
                // Boucler sur les fichiers
                $fileData = ImageManager::uploads($file, $path);

                // Créer un nouveau document
                $document = Document::update([
                    'type' => 'justificatif',
                    'nom' => 'justificatif_demande_' . $conger->id.'_'.now(),
                    'chemin_doc' => $fileData['filePath'],
                    'user_id' => 1,
                    'demande_id' => $conger->id,
                ]);
            }

        }

        // dd($dem_enregistre);
        //! envoie du mail de confirmation
            # code...

        // dd($dem->id);
        // envoie de notification pour validation
        // $this->notification($demande->id);

        $conger->update($validated);

        return response()->json(['message' => 'Demande mis à jour avec succès']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(conger $id)
    {
        $id->delete();

        return response()->json(['message' => 'Demande supprimé avec succès']);
    }
}
