<?php

namespace App\Http\Controllers;

use App\Mail\mail_demandes;
use App\Models\circuit_organe;
use App\Models\organe_validateur;
use App\Models\dem_vers_objet;
use App\Models\demande;
use App\Models\type_demande;
use App\Models\document;
use App\Models\notification_demande;
use App\Models\type_organe_validateur;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\dem_objet_sg;
use App\Models\dem_objet_g;
use App\Models\dem_objet;
use App\Models\filliale;
use App\Models\TravelRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ImageManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Response;
// use Illuminate\Support\Carbon;
use Cmixin\BusinessDay;
use Illuminate\Support\Facades\Log;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailySummaryReport;
use App\Models\Historique;
use App\Traits\TracksUserActions;
use Spatie\Activitylog\Models\Activity;

// class DemandeController extends Controller
// {
//     // use TracksUserActions;
//     /**
//      * Display a listing of the resource.
//      */
//     public function index(Request $request)
//     {
//         //
//         $types = type_demande::all();

//         $query = demande::where('user_id', 1)->with('types')->with('objets')->with('objetsg')->with('user')->with('documents')->with('notification')->orderBy('created_at', 'desc');

//         // Filtrer par type_id si un type a été sélectionné
//         if ($request->has('type_id') && !empty($request->type_id)) {
//             $query->where('type_demandes_id', $request->type_id); // Ajouter la condition de filtrage par type_id
//         }

//         // Récupérer les demandes après application du filtrage
//         $demande = $query->get();

//         // dd($demande, $request);

//         $demande->map(function ($demande) {
//             $demande->procces_valide_result = $demande->procces_valide();
//             $demande->nombre_notifications = $demande->notification->count();
//             // dd($demande->procces_valide_result);
//             return $demande;
//         });

//         $demande_a_traiter = notification_demande::whereNotNull('demande_id')->where('statut', 0)->where('user_id', 1)->with('demande')->whereHas('demande', function ($query) {
//             // Assuming demande has not been soft-deleted
//             $query->whereNull('deleted_at');
//         })->orderBy('created_at', 'desc')->get();

//         $demande_traiter = notification_demande::whereNotNull('demande_id')
//         ->where(function ($query) {
//             $query->where('statut', 1)
//                 ->orWhere('statut', 2);
//         })
//         ->where('user_id', 1)
//         ->with('demande')
//         ->whereHas('demande', function ($query) {
//             // Assuming demande has not been soft-deleted
//             $query->whereNull('deleted_at');
//         })
//         ->orderBy('created_at', 'desc')
//         ->get();

//         $unprocessedDemandCount = notification_demande::whereNotNull('demande_id')
//         ->where('statut', 0)
//         ->where('user_id', 1)
//         ->whereHas('demande', function ($query) {
//             $query->whereNull('deleted_at');
//         })
//         ->count();
//         $profiles = UserProfile::with('user')->with('directions')->orderBy('created_at', 'desc')->get();
//         // notification_demande::whereNotNull('demande_id')->where('statut', 1)->whereHas('demande', function($query) {$query->where('user_id', 1);})->with('demande')->orderBy('created_at', 'desc')->get();
//         // dd($demande_a_traiter);
//         $user = User::with('userProfile')->find(1);
//         // $demande = UserProfile::with('directions_id')->find(auth()->user()->directions_id);

//         if($request->ajax()){
//             $response = [
//             'demande' => $demande,
//             'demande_a_traiter' => $demande_a_traiter,
//             'demande_traiter' => $demande_traiter,
//             'profiles' => $profiles,
//             ];

//             // Condition pour inclure ou non 'unprocessed_demand_count' dans la réponse
//             if ($unprocessedDemandCount != 0) {
//                 $response['unprocessed_demand_count'] = $unprocessedDemandCount;
//             } else {
//                 $response['unprocessed_demand_count'] = '';
//             }

//             return response()->json($response);
//             // dd($request);
//         }
//         return response()->json($demande);

//         // return view('pages.demande', ['demande'=>$demande, 'user'=>$user, 'types'=>$types]);
//     }

//     public function indexobjet(Request $request)
//     {

//         $dem_objet_g = dem_objet_g::all();
//         $dem_objet = dem_objet::all();
//         $dem_objet_sg = dem_objet_sg::all();

//         return response()->json($dem_objet);
//     }

//     public function indexhistorique(Request $request)
//     {
//         // Récupérer les actions de l'utilisateur connecté
//         // $activities = Activity::with('causer')->get();

//         return view('traceability.index', compact('activities'));
//     }

//     public function indexdemtraiter(Request $request)
//     {
//         //
//         $demande = demande::where('user_id', 1)->with('types')->with('objets')->with('objetsg')->with('user')->with('documents')->with('notification')->orderBy('created_at', 'desc')->get();
//         // dd($demande, $request);
//         $travel  = TravelRequest::where('users_id', 1)->with('types')->with('documents')->with('notification')->orderBy('created_at', 'desc')->get();
//         // dd($demande, $request);

//         $demande->map(function ($demande) {
//             $demande->procces_valide_result = $demande->procces_valide();
//             $demande->nombre_notifications = $demande->notification->count();
//             // dd($demande->procces_valide_result);
//             return $demande;
//         });

//         $travel->map(function ($travel) {
//             $travel->procces_valide_result = $travel->procces_valide();
//             $travel->nombre_notifications = $travel->notification->count();
//             // dd($demande->procces_valide_result);
//             return $travel;
//         });

//         $demande_a_traiter = notification_demande::whereNotNull('demande_id')->where('statut', 0)->where('user_id', 1)->with('demande')->whereHas('demande', function ($query) {
//             // Assuming demande has not been soft-deleted
//             $query->whereNull('deleted_at');
//         })->orderBy('created_at', 'desc')->get();



//         $travel_a_traiter = notification_demande::whereNotNull('travel_id')->where('statut', 0)->where('user_id', 1)->with('travel')->whereHas('travel', function ($query) {
//             // Assuming demande has not been soft-deleted
//             $query->whereNull('deleted_at');
//         })->orderBy('created_at', 'desc')->get();

//         $demande_traiter = filliale::with(['demande' => function($query) {
//             $query->whereHas('notification', function($query) {
//                 $query->whereNotNull('demande_id')
//                       ->where(function ($query) {
//                           $query->where('statut', 1)
//                                 ->orWhere('statut', 2);
//                       })
//                       ->where('user_id', Auth::user()->id)
//                       ->orderBy('created_at', 'desc');
//             })
//             ->whereNull('deleted_at');
//         }])->get();

//         $travel_traiter = filliale::with(['travel' => function($query) {
//             $query->whereHas('notification', function($query) {
//                 $query->whereNotNull('travel_id')
//                       ->where(function ($query) {
//                           $query->where('statut', 1)
//                                 ->orWhere('statut', 2);
//                       })
//                       ->where('user_id', Auth::user()->id)
//                       ->orderBy('created_at', 'desc');
//             })
//             ->whereNull('deleted_at');
//         }])->get();

//         $unprocessedDemandCount = notification_demande::whereNotNull('demande_id')
//         ->where('statut', 0)
//         ->where('user_id', 1)
//         ->whereHas('demande', function ($query) {
//             $query->whereNull('deleted_at');
//         })
//         ->count();
//         // notification_demande::whereNotNull('demande_id')->where('statut', 1)->whereHas('demande', function($query) {$query->where('user_id', 3);})->with('demande')->orderBy('created_at', 'desc')->get();
//         // dd($demande_a_traiter);
//         $user = User::with('userProfile')->find(1);
//         // $demande = UserProfile::with('directions_id')->find(auth()->user()->directions_id);

//         if($request->ajax()){
//             $response = [
//             'travel' => $travel,
//             'demande' => $demande,
//             'tavel_a_traiter' => $travel_a_traiter,
//             'demande_a_traiter' => $demande_a_traiter,
//             'demande_traiter' => $demande_traiter,
//             'travel_traiter' => $travel_traiter,
//             ];

//             // Condition pour inclure ou non 'unprocessed_demand_count' dans la réponse
//             if ($unprocessedDemandCount != 0) {
//                 $response['unprocessed_demand_count'] = $unprocessedDemandCount;
//             } else {
//                 $response['unprocessed_demand_count'] = '';
//             }

//             return response()->json($response);
//         }
//         return view('pages.demande_traiter', ['demande_traiter'=>$demande_traiter, 'travel_traiter'=>$travel_traiter, 'user'=>$user]);
//     }

//     public function getTables(Request $request)
//     {
//         if ($request->ajax()) {
//             $file_path = storage_path('Alltables.json');
//             return response()->download($file_path);
//         }
//     }

//     /**
//      * Show the form for creating a new resource.
//      */
//     public function create(Request $request)
//     {
//         //
//         // dd($request);

//         $dem_objet_g = dem_objet_g::all();
//         $dem_objet = dem_objet::all();
//         $filliale = filliale::all();
//         // $profile = UserProfile::where('user_id', 3)->first();
//         // $dateDepart = $request->date_depart;
//         // dd($dateDepart);
//         if($request->classes == 2)
//         {
//             $profile = UserProfile::where('user_id', 1)->first();

//             $dateEmbauche = Carbon::createFromDate($profile->date_embauche);
//             // dd($dateEmbauche);

//             // Simuler la date actuelle (à remplacer par la vraie date actuelle)
//             $dateActuelle = Carbon::now();

//             // Calcul de la différence en mois
//             $differenceEnMois = ceil($dateActuelle->floatDiffInMonths($dateEmbauche));

//             // dd($dateEmbauche, $differenceEnMois);

//             $compa = $differenceEnMois > 6;

//             // dd($compa);
//             $getuser = user::find(1);
//             $getuser['differenceEnMois'] = $differenceEnMois;

//             // dd($dateEmbauche,$dem_objet_g, $profile);

//             if ($compa == true)
//             {
//                 return view($request->view,
//                 [
//                     'dem_objet_gs'=>$dem_objet_g,
//                     'dem_objets'=>$dem_objet,
//                     // response()->json(['date_fin' => $dateDepart + 30]),
//                     'filliales' => $filliale,
//                     'user' => $profile
//                 ]);
//             }

//             else if ($profile->isEmbauche == 0)
//             {
//                 return view($request->view,
//                 [
//                     'dem_objet_gs'=>$dem_objet_g,
//                     'dem_objets'=>$dem_objet,
//                     // response()->json(['date_fin' => $dateDepart + 30]),
//                     'filliales' => $filliale,
//                     'user' => $profile
//                 ]);
//             }

//             else {
//                 (new SendEmailController())->NotificationRejet($getuser);

//                 // Si la différence en mois est inférieure ou égale à 6, retournez la vue avec le message d'erreur
//                 return response()-> json([
//                     'error' => false,
//                     'message' => 'Vous ne pouvez pas faire cette demande car vous avez moins de 6 mois d\'ancienneté. Conformement à la convention collective du droit ivoirien vous ne pouvez en aucun cas faire une demande de permission merci.'
//                 ]);
//             }
//         }
//         else if($request->classes == 3)
//         {
//             $profile = UserProfile::where('user_id', 1)->first();

//             $dateEmbauche = Carbon::createFromDate($profile->date_embauche);
//             // dd($dateEmbauche);

//             // Simuler la date actuelle (à remplacer par la vraie date actuelle)
//             $dateActuelle = Carbon::now();

//             // Calcul de la différence en mois
//             $differenceEnMois = ceil($dateActuelle->floatDiffInMonths($dateEmbauche));

//             // dd($dateEmbauche, $differenceEnMois);

//             $compa = $differenceEnMois > 12;

//             // dd($compa);
//             $getuser = user::find(1);
//             $getuser['differenceEnMois'] = $differenceEnMois;

//             if ($compa == true)
//             {
//                 // return view($request->view,
//                 // [
//                 //     'dem_objet_gs'=>$dem_objet_g,
//                 //     'dem_objets'=>$dem_objet,
//                 //     // response()->json(['date_fin' => $dateDepart + 30]),
//                 //     'filliales' => $filliale,
//                 //     'user' => $profile
//                 // ]);
//                 return response()->json($dem_objet_g , $dem_objet);
//             }
//             else if ($profile->isEmbauche == 0)
//             {
//                 // return view($request->view,
//                 // [
//                 //     'dem_objet_gs'=>$dem_objet_g,
//                 //     'dem_objets'=>$dem_objet,
//                 //     // response()->json(['date_fin' => $dateDepart + 30]),
//                 //     'filliales' => $filliale,
//                 //     'user' => $profile
//                 // ]);
//                 return response()->json($dem_objet_g , $dem_objet);
//             }
//             else {
//                 (new SendEmailController())->NotificationRejet($getuser);

//                 // Si la différence en mois est inférieure ou égale à 6, retournez la vue avec le message d'erreur
//                 return response()-> json([
//                     'error' => false,
//                     'message' => 'Vous ne pouvez pas faire cette demande car vous avez moins de 1 ans d\'ancienneté. Conformement à la convention collective ivoirienne vous n\'avez pas droit à un congé, merci pour votre comprehension.'
//                 ]);
//             }
//         }
//         else
//         {
//             // return view($request->view,
//             // [
//             //     'dem_objet_gs'=>$dem_objet_g,
//             //     'dem_objets'=>$dem_objet,
//             //     // response()->json(['date_fin' => $dateDepart + 30]),
//             //     'filliales' => $filliale,
//             //     'user' => $profile
//             // ]);
//             return response()->json($dem_objet_g , $dem_objet);
//         };
//     }

//     public function calculerDateFin(Request $request)
//     {
//         try {
//             $userStartDate = $request->input('userStartDate');
//             $nombreDeJour = $request->input('nombreDeJour');

//             // Vérifiez que les entrées sont valides
//             if (!$userStartDate || !$nombreDeJour || !is_numeric($nombreDeJour)) {
//                 return response()->json([
//                     'error' => true,
//                     'message' => 'Les données fournies sont invalides. Veuillez vérifier la date et le nombre de jours.',
//                 ], 400);
//             }

//             // Convertir en instance Carbon
//             $userStartDate = Carbon::parse($userStartDate);
//             $now = Carbon::now();

//             // Vérifiez si la demande respecte le préavis de 48 heures
//             $minimumAdvanceNotice = $userStartDate->copy()->subHours(48);
//             if ($now->gte($minimumAdvanceNotice)) {
//                 return response()->json([
//                     'error' => false,
//                     'message' => 'La demande doit être faite au moins 48 heures avant votre date de départ.',
//                 ]);
//             }


//             // Calculer la date de fin en tenant compte des jours ouvrables et des jours fériés
//             $endDates = $this->addBusinessDaysWithHolidays($userStartDate, $nombreDeJour);

//             // Ajouter un jour supplémentaire à la date de fin pour la reprise à 08h00
//             $endDates = Carbon::parse($endDates)->addDay(); // Ajout d'un jour pour la reprise à 08h00

//             // Ajouter un message supplémentaire
//             $message = "Pour cette demande, vous quitterez l'entreprise le " . $userStartDate->format('d-m-Y') .
//                 " à 17h30 et vous reprenez le service le " . $endDates->format('d-m-Y') . " à 08h00.";

//             return response()->json(['endDate' => $endDates->format('Y-m-d'), 'message' => $message]);
//         } catch (\Exception $e) {
//             // Log l'erreur pour faciliter le débogage
//             // \Log::error('Erreur lors du calcul de la date de fin : ' . $e->getMessage());

//             return response()->json([
//                 'error' => true,
//                 'message' => 'Une erreur est survenue lors du calcul de la date de fin. Veuillez réessayer.',
//             ], 500);
//         }
//     }

//     private function addBusinessDaysWithHolidays($startDate, $days)
//     {
//         try {
//             $current = Carbon::parse($startDate);

//             $isWeekend = function ($date) {
//                 return $date->isWeekend();
//             };

//             $annee = date('Y');

//             // Charger les jours fériés
//             $holidays = [];
//             if (Storage::exists($annee . '.json')) {
//                 $holidays = json_decode(Storage::get($annee . '.json'), true);
//             }

//             $isHoliday = function ($date) use ($holidays) {
//                 foreach ($holidays as $holiday) {
//                     $holidayDate = is_array($holiday) ? $holiday['date'] : $holiday;
//                     if (Carbon::parse($holidayDate)->isSameDay($date)) {
//                         return true;
//                     }
//                 }
//                 return false;
//             };

//             // Ajouter les jours ouvrables
//             while ($days > 0) {
//                 $current->addDay();

//                 if (!$isWeekend($current) && !$isHoliday($current)) {
//                     $days--;
//                 }
//             }

//             // Vérifiez si la date finale tombe sur un week-end
//             if ($current->isWeekend()) {
//                 if ($current->isSaturday()) {
//                     $current->addDays(2); // Passer au lundi
//                 } elseif ($current->isSunday()) {
//                     $current->addDay(); // Passer au lundi
//                 }
//             }

//             return $current->format('Y-m-d');
//         } catch (\Exception $e) {
//             // \Log::error('Erreur dans addBusinessDaysWithHolidays : ' . $e->getMessage());
//             throw $e;
//         }
//     }


//     /*public function calculerDateFin(Request $request)
//     {
//         try {
//             $userStartDate = $request->input('userStartDate');
//             $nombreDeJour = $request->input('nombreDeJour');

//             // Check if the user request is made at least 48 hours before the chosen start date
//             $minimumAdvanceNotice = Carbon::parse($userStartDate)->subHours(48);
//             $now = Carbon::now();

//             if ($now->gte($minimumAdvanceNotice)) {
//                 return response()-> json([
//                         'error' => false,
//                         'message' => 'La demande doit être faite au moins 48 heures avant votre date de départ.',
//                 ]);
//             }

//             // Utilisez la méthode existante pour calculer la date de fin
//             $endDate = $this->addBusinessDaysWithHolidays($userStartDate, $nombreDeJour);

//             // Ajoutez un message supplémentaire
//             $message = "Pour cette demande, vous quitterez l'entreprise le " . Carbon::parse($userStartDate)->format('d-m-Y') . " à 17h30 et vous reprenez le service le " . Carbon::parse($endDate)->format('d-m-Y') . " à 08h00.";

//             return response()->json(['endDate' => $endDate, 'message' => $message]);
//         } catch (\Exception $e) {
//             dd($e);
//             // Log l'erreur
//             // Log::error('Erreur lors du calcul de la date de fin : ' . $e->getMessage());

//             // Retourner une réponse d'erreur spécifique à la condition de 48 heures
//             return response()->json(['error' => $e->getMessage()]);
//         }
//     }

//     private function addBusinessDaysWithHolidays($startDate, $days)
//     {
//         try {
//             $current = Carbon::parse($startDate);

//             $isWeekend = function ($date) {
//                 return $date->isWeekend();
//             };
//             $annee = date('Y');
//             $holidays = json_decode(Storage::get($annee.'.json'), true);

//             $isHoliday = function ($date) use ($holidays) {
//                 foreach ($holidays as $holiday) {
//                     $holidayDate = is_array($holiday) ? $holiday['date'] : $holiday; // Ajout de cette ligne
//                     if (Carbon::parse($holidayDate)->isSameDay($date)) {
//                         return true;
//                     }
//                 }
//                 return false;
//             };

//             while ($days > 0) {
//                 // Assurez-vous que $current est bien initialisé avec une valeur valide
//                 $current->addDay();

//                 // Vérifiez si la date actuelle n'est ni un week-end ni un jour férié
//                 if (!$isWeekend($current) && !$isHoliday($current)) {
//                     $days--;
//                 }
//             }

//             $adjustedEndDate = Carbon::parse($current)->addDay();

//             // Check if the adjusted end date falls on a weekend
//             if ($adjustedEndDate->isWeekend()) {
//                 if ($adjustedEndDate->isSaturday()) {
//                     $adjustedEndDate->addDays(2); // If it's Saturday, add 2 days to make it Monday
//                 } elseif ($adjustedEndDate->isSunday()) {
//                     $adjustedEndDate->addDay(); // If it's Sunday, add 1 day to make it Monday
//                 }
//             }
//             return $adjustedEndDate->format('Y-m-d');
//         } catch (\Exception $e) {
//             // \Log::error('Erreur lors du calcul de la date de fin : ' . $e->getMessage());
//             throw $e; // Vous pouvez choisir de gérer l'exception en conséquence
//         }
//     }*/

//     public function liste($label)
//     {
//         // dd($label);
//         $liste_dem_sg = dem_objet_sg::where('dem_objet_g_id', $label)->with('objets')->get();
//         // dd($liste_dem_sg);
//         return response()-> json([
//             'labels' => $liste_dem_sg,
//         ]);
//     }

//     /**
//      * Store a newly created resource in storage.
//      */
//     public function store(Request $request)
//     {
//         //
//         // dd($request);
//         Log::info('Store API called', $request->all());


//         DB::beginTransaction();

//         try
//         {
//             // $user = User::with('userProfile')->find(1);
//             // $request['user_id'] = 1;
//             // $request['direction_id'] = $user->userProfile->directions_id;
//             // $request['filliale_id'] = $user->userProfile->filliale_id;
//             // $request['dem_objet_id'] = implode(',', $request['dem_objet_id']);

//             // $request['dem_objet_id'] = $user->dem_objet_id;
//                 $validated  = $request->validate
//                 ([
//                     'montant_demande' => 'integer|nullable',
//                     'motif_permi' => 'string|nullable',
//                     'motif' => 'string|nullable',
//                     'detail' => 'string|nullable',
//                     'payement' => 'integer|nullable',
//                     'lieu_travail' => 'string|nullable',
//                     'heure_debut' => 'string|nullable',
//                     'heure_fin' => 'string|nullable',
//                     'date_depart' => 'date|nullable',
//                     'date_fin' => 'date|nullable',
//                     'type' => 'string|nullable',
//                     'nombre_de_jours'=>'integer|nullable',
//                     'user_id'=>'integer|nullable',
//                     'procces_valide_result'=>'json|nullable',
//                     'direction_id'=>'integer|required|nullable',
//                     'filliale_id'=>'integer|required|nullable',
//                     'type_demandes_id'=>'integer|required|nullable'
//                 ]);

//                 $dem = demande::create([
//                     'montant_demande' => $validated['montant_demande'],
//                     'motif_permi' => $validated['motif_permi'],
//                     'motif' => $validated['motif'],
//                     'detail' => $validated['detail'],
//                     'payement' => $validated['payement'],
//                     'lieu_travail' => $validated['lieu_travail'],
//                     'heure_debut' => $validated['heure_debut'],
//                     'heure_fin' => $validated['heure_fin'],
//                     'date_depart' => $validated['date_depart'],
//                     'date_fin' => $validated['date_fin'],
//                     'type' => $validated['type'],
//                     'nombre_de_jours' => $validated['nombre_de_jours'],
//                     'user_id' => 1,
//                     'procces_valide_result' => $validated['procces_valide_result'],
//                     'direction_id' => $validated['direction_id'],
//                     'filliale_id' => $validated['filliale_id'],
//                     'type_demandes_id' => $validated['type_demandes_id'],
//                     'created_by' => auth()->id() ?? 1,
//                 ]);
//                 // dd($validated);
//                 // ...
//                 // $demandesExistantes ="";
//                 // if ($request->type_demandes_id==3) {
//                 //     # code...
//                 //     // Ajoutez cette ligne après la récupération de la direction de l'utilisateur
//                 //     $demandesExistantes = Demande::where('type_demandes_id', 3) // Assurez-vous que 3 est l'ID du type de demande de congé
//                 //     ->where('statut', 1)
//                 //     ->where('direction_id', $user->userProfile->directions_id)
//                 //     ->get();

//                 //     $request['motif_permi']="CONGES";
//                 // }

//                 // // dd($validated);
//                 // if (is_object($demandesExistantes) && $demandesExistantes->count() > 0) {
//                 //     foreach ($demandesExistantes as $key => $demandesExistante) {
//                 //         // code...
//                 //         $checkDate1 = Carbon::parse($request->date_depart); // Replace with your actual date
//                 //         $checkDate2 = Carbon::parse($request->date_fin); // Replace with your actual date

//                 //         $startDate = Carbon::parse($demandesExistante->date_depart);
//                 //         $endDate = Carbon::parse($demandesExistante->date_fin);

//                 //         $isWithinRange1 = $checkDate1->between($startDate, $endDate);
//                 //         $isWithinRange2 = $checkDate2->between($startDate, $endDate);

//                 //         if ($isWithinRange1 && $isWithinRange2) {
//                 //             if ($demandesExistante->accords->count() > 0) {
//                 //             // Si des demandes de congé existent dans la même direction et la même plage de dates, bloquez la nouvelle demande
//                 //                 return response()->json([
//                 //                     'error' => 1,
//                 //                     'message' => 'La periode demandée est déja occupée par un autre congé.'
//                 //                 ]);
//                 //             }
//                 //         }
//                 //         // else {
//                 //         //   echo "The date $checkDate falls outside the range [$startDate, $endDate]";
//                 //         // }
//                 //     }
//                 // }
//                 // dd(is_object($demandesExistantes));

//                 return response()->json(['message' => 'demande créé avec succès']);


//                 // enregistrement des objets

//                 // if(is_array($request['dem_objet_id'])  && !empty($request['dem_objet_id']))
//                 // {
//                 //     foreach($request['dem_objet_id'] as $objetselect)
//                 //     {
//                 //         dem_vers_objet::create([
//                 //             'dem_objets_id' => $objetselect,
//                 //             'demandes_id' => $dem->id,
//                 //             'classe' => $dem->type_demandes_id == 1? dem_objet_sg::class : dem_objet::class,
//                 //         ]);
//                 //     }
//                 // // $collection = $dem->objets->implode('label', ', ');
//                 // // dd($collection, $dem->objets);
//                 // }

//                 // else
//                 // {
//                 //     dem_vers_objet::create([
//                 //         'dem_objets_id' => $request['dem_objet_id'],
//                 //         'demandes_id' => $dem->id,
//                 //         'classe' => $dem->type_demandes_id == 1? dem_objet_sg::class : dem_objet::class,
//                 //     ]);
//                 // }
//                 // $dem_enregistre = demande::where('id', $dem->id, $dem->objet)->with('user', 'direction', 'types', 'objets')->first();

//                 // //enregistrement de document
//                 // $path = 'Documents/';
//                 // // Vérifier s'il existe des fichiers
//                 // if ($request->files->count() > 0 )
//                 // {
//                 //     $files = $request->files;

//                 //     foreach ($files as $file) {
//                 //         // Boucler sur les fichiers
//                 //         $fileData = ImageManager::uploads($file, $path);

//                 //         // Créer un nouveau document
//                 //         $document = Document::create([
//                 //             'type' => 'justificatif',
//                 //             'nom' => 'justificatif_demande_' . $dem->id.'_'.now(),
//                 //             'chemin_doc' => $fileData['filePath'],
//                 //             'user_id' => 1,
//                 //             'demande_id' => $dem->id,
//                 //         ]);
//                 //     }

//                 // }

//                 // // dd($dem_enregistre);
//                 // //! envoie du mail de confirmation
//                 //     # code...

//                 // // dd($dem->id);
//                 // // envoie de notification pour validation
//                 // $this->notification($dem->id);

//                 DB::commit();
//                 // dd($dem);
//                 return response()-> json([
//                     'error' => 0,
//                     'message' => 'La demande a été enregistrée.'
//                 ]);

//         } catch (\Exception $e)
//         {
//             dd($e);
//             DB::rollBack();
//             // Gestion de l'erreur
//             if ($e->getPrevious()) {
//                 // code...
//                 $message=$e->getPrevious()->getMessage();
//             } else {
//                 // code...
//                 $message=$e->getMessage();
//             }

//             return response()-> json([
//                 'error' => 1,
//                 'message' => $message,
//             ]);
//         }

//     }

//     public function visualiser($id)
//     {
//         $documents = document::where('demande_id', $id)->get();

//         //

//         // Construire le chemin complet du fichier
//         $cheminFichier = [];
//         foreach($documents as $document)
//         {
//             $cheminFichier[] = ('storage/public/' . $document->chemin_doc);

//             // Vérifier si le fichier existe
//             // if (!file_exists('storage/' . $document->chemin_doc)) {
//             //     abort(404);
//             // }
//         }
//         // dd($document, $cheminFichier);


//         return view('modals.document_justificatif',['document'=> $cheminFichier]);

//         // Retourner le fichier en tant que réponse
//         // return Response::file($cheminFichier);
//     }

//     public function renvoi_mail(demande $demande)
//     {
//         //
//         $dem_enregistre = notification_demande::where('demande_id', $demande->id)->where('statut', 0)->with('demande')->first();

//             // dd($dem_enregistre);
//             //! envoie du mail de confirmation
//                 # code...
//         $this->notification($dem_enregistre->id);

//         (new SendEmailController())->NotificationMail($dem_enregistre, user::find($dem_enregistre->user_id));
//     }

//     public function renvoi(demande $demande)
//     {
//         //
//         $dem_enregistre = demande::where('id', $demande->id)->with('user', 'direction', 'types', 'objets')->first();

//             // dd($dem_enregistre);
//             //! envoie du mail de confirmation
//                 # code...


//                 // (new SendEmailController())->NotificationMail($dem_enregistre);
//                 return view('emails.mail_demandes', ['demande_mails'=>$dem_enregistre]);
//     }

//     /**
//      * Display the specified resource.
//      */
//     public function show(demande $demande)
//     {
//         //
//     }

//     // public function liste()
//     // {
//     //     //
//     //     $dem_objet_g = dem_objet_g::all();
//     //     dd($dem_objet_g);
//     //     return view('demande',[$dem_objet_g]);
//     // }

//     /**
//      * Show the form for editing the specified resource.
//      */
//     public function edit($id)
//     {
//         //
//         $Demande = demande::where('id', $id)->with('types')->with('objets')->with('objetsg')->with('user')->with('documents')->with('notification')->first();
//         // dd($Demande, $Demande->user->userProfile);
//         if ($Demande -> type_demandes_id == 1)
//         {

//             return view('edit.demande_bien_et_service',
//             [
//                 'id' => $Demande->id,
//                 'selectdemande'=> demande::where('id', $Demande->id)->with('objets')->with('objetsg')->get(),
//                 // premiere recupere les elements de dem_objet_g et dem_objet
//                 'dem_objet_g'=> dem_objet_g::all(),
//                 'dem_objet'=> dem_objet_sg::with('objets')->get(),
//                 'montant_demande'=>$Demande->montant_demande,
//                 'detail'=>$Demande->detail
//             ]);
//         }
//         if ($Demande -> type_demandes_id == 2)
//         {
//             // dd($Demande->direction);
//             return view('edit.demande_permission',
//             [
//                 'id' => $Demande->id,
//                 'selectdemande'=> demande::where('id', $Demande->id)->with('objets')->get(),
//                 // premiere recupere les elements de dem_objet_g et dem_objet
//                 'dem_objet'=> dem_objet::all(),
//                 // 'dem_objet'=> dem_objet_sg::with('objets')->get(),
//                 // 'selectfilliales'=> demande::where('id', $Demande->id)->with('filliales')->get(),
//                 'filliales' => filliale::all(),
//                 'motif_permi'=> $Demande->motif_permi,
//                 'user'=> $Demande->user->userProfile,
//                 'date_depart' => $Demande->date_depart,
//                 'date_fin' => $Demande->date_fin
//             ]);
//         }
//         if ($Demande -> type_demandes_id == 3)
//         {

//             return view('edit.demande_conge',
//             [
//                 'id' => $Demande->id,
//                 'selectdemande'=> demande::where('id', $Demande->id)->with('objets')->get(),
//                 // premiere recupere les elements de dem_objet_g et dem_objet
//                 'dem_objet'=> dem_objet::all(),
//                 // 'dem_objet'=> dem_objet_sg::with('objets')->get(),
//                 'selectfilliale'=> demande::where('id', $Demande->id)->with('filliales')->get(),
//                 'user'=> $Demande->user->userProfile,
//                 'nombre_de_jours' => $Demande->nombre_de_jours,
//                 'date_depart' => $Demande->date_depart,
//                 'date_fin' => $Demande->date_fin
//             ]);
//         }
//         if ($Demande -> type_demandes_id == 4)
//         {
//             // dd(demande::where('id', $Demande->id)->with('objets')->get());
//             return view('edit.demande_absence',
//             [
//                 'id' => $Demande->id,
//                 'selectdemande'=> demande::where('id', $Demande->id)->with('objets')->get(),
//                 // premiere recupere les elements de dem_objet_g et dem_objet
//                 'dem_objet'=> dem_objet::all(),
//                 // 'dem_objet'=> dem_objet_sg::with('objets')->get(),
//                 // 'selectfilliales'=> demande::where('id', $Demande->id)->with('filliales')->get(),
//                 'filliales' => filliale::all(),
//                 'motif_permi'=> $Demande->motif_permi,
//                 'user'=> $Demande->user->userProfile,
//                 'date_depart' => $Demande->date_depart,
//                 'date_fin' => $Demande->date_fin
//             ]);
//         };
//         // return response()->json([
//         //    "idDemande"=>$id,
//         // ]);
//     }

//     /**
//      * Update the specified resource in storage.
//      */
//     public function update(Request $request, demande $Demande)
//     {
//         //
//         $user = User::with('userProfile')->find(1);
//         $request['directions_id'] = $user->userProfile->directions_id;
//         $validated  = $request->validate
//         ([
//             'objet' => 'string|nullable',
//             'montant_demande' => 'integer|nullable',
//             'motif_permi' => 'string|nullable',
//             'motif' => 'string|nullable',
//             'detail' => 'string|nullable',
//             'payement' => 'integer|nullable',
//             'lieu_travail' => 'string|nullable',
//             'moment' => 'string|nullable',
//             'periode' => 'string|nullable',
//             'nombre_de_jours'=>'integer|nullable',
//             'date_depart' => 'date|nullable',
//             'date_fin' => 'date|nullable',
//             'type' => 'string|nullable',
//             'directions_id'=>'integer|required|nullable',
//             'type_demandes_id'=>'integer|required|nullable'
//         ]);

//         // dd($Demande);

//         // $Demande->update($validated);

//         // dem_vers_objet::where('demandes_id', $Demande->id)->delete();

//         // if(is_array($request['dem_objet_id'])  && !empty($request['dem_objet_id']))
//         // {
//         //     foreach($request['dem_objet_id'] as $objetselect)
//         //     {
//         //         dem_vers_objet::create([
//         //             'dem_objets_id' => $objetselect,
//         //             'demandes_id' => $Demande->id
//         //         ]);
//         //     }
//         // }

//         // else
//         // {
//         //     dem_vers_objet::create([
//         //         'dem_objets_id' => $request['dem_objet_id'],
//         //         'demandes_id' => $Demande->id
//         //     ]);
//         // }

//         $demandesExistantes ="";
//         if ($request->type_demandes_id==1) {
//             # code...
//             // Ajoutez cette ligne après la récupération de la direction de l'utilisateur
//             $demandesExistantes = Demande::where('type_demandes_id', 3) // Assurez-vous que 3 est l'ID du type de demande de congé
//             ->where('statut', 1)
//             ->where('direction_id', $user->userProfile->directions_id)
//             ->get();

//             $request['motif_permi']="CONGES";
//         }

//         // dd($validated);
//         if (is_object($demandesExistantes) && $demandesExistantes->count() > 0) {
//             foreach ($demandesExistantes as $key => $demandesExistante) {
//                 // code...
//                 $checkDate1 = Carbon::parse($request->date_depart); // Replace with your actual date
//                 $checkDate2 = Carbon::parse($request->date_fin); // Replace with your actual date

//                 $startDate = Carbon::parse($demandesExistante->date_depart);
//                 $endDate = Carbon::parse($demandesExistante->date_fin);

//                 $isWithinRange1 = $checkDate1->between($startDate, $endDate);
//                 $isWithinRange2 = $checkDate2->between($startDate, $endDate);

//                 if ($isWithinRange1 && $isWithinRange2) {
//                     if ($demandesExistante->accords->count() > 0) {
//                     // Si des demandes de congé existent dans la même direction et la même plage de dates, bloquez la nouvelle demande
//                         return response()->json([
//                             'error' => 1,
//                             'message' => 'La periode demandée est déja occupée par un autre congé.'
//                         ]);
//                     }
//                 }
//                 // else {
//                 //   echo "The date $checkDate falls outside the range [$startDate, $endDate]";
//                 // }
//             }
//         }
//         // dd(is_object($demandesExistantes));
//         // $Demande = demande::update($validated);
//         Demande::where('id', $Demande)->update($request->all());

//         // enregistrement des objets

//         if(is_array($request['dem_objet_id'])  && !empty($request['dem_objet_id']))
//         {
//             foreach($request['dem_objet_id'] as $objetselect)
//             {
//                 dem_vers_objet::update([
//                     'dem_objets_id' => $objetselect,
//                     'demandes_id' => $Demande->id,
//                     'classe' => $Demande->type_demandes_id == 1? dem_objet_sg::class : dem_objet::class,
//                 ]);
//             }
//         // $collection = $dem->objets->implode('label', ', ');
//         // dd($collection, $dem->objets);
//         }

//         else
//         {
//             dem_vers_objet::update([
//                 'dem_objets_id' => $request['dem_objet_id'],
//                 'demandes_id' => $Demande->id,
//                 'classe' => $Demande->type_demandes_id == 1? dem_objet_sg::class : dem_objet::class,
//             ]);
//         }
//         $dem_enregistre = demande::where('id', $Demande->id, $Demande->objet)->with('user', 'direction', 'types', 'objets')->first();

//         //enregistrement de document
//         $path = 'Documents/';
//         // Vérifier s'il existe des fichiers
//         if ($request->files->count() > 0 )
//         {
//             $files = $request->files;

//             foreach ($files as $file) {
//                 // Boucler sur les fichiers
//                 $fileData = ImageManager::uploads($file, $path);

//                 // Créer un nouveau document
//                 $document = Document::update([
//                     'type' => 'justificatif',
//                     'nom' => 'justificatif_demande_' . $Demande->id.'_'.now(),
//                     'chemin_doc' => $fileData['filePath'],
//                     'user_id' => 1,
//                     'demande_id' => $Demande->id,
//                 ]);
//             }

//         }

//         // dd($dem_enregistre);
//         //! envoie du mail de confirmation
//             # code...

//         // dd($dem->id);
//         // envoie de notification pour validation
//         // $this->notification($dem->id);

//         DB::commit();
//         // dd($dem);
//         return response()-> json([
//             'error' => 0,
//             'message' => 'La demande a été modifiée.'
//         ]);
//     }

//     public function notificationupdate(Request $request, $notification)
//     {
//         $notificationsArray = json_decode($notification);
//         $results = [];

//         foreach ($notificationsArray as $notificationId) {
//             $notification = notification_demande::find($notificationId);

//             if (!$notification) {
//                 $results[] = [
//                     'id' => $notificationId,
//                     'error' => 1,
//                     'message' => 'Notification non trouvée.'
//                 ];
//                 continue;
//             }

//             $demande_recupere = demande::find($notification->demande_id);
//             if (!$demande_recupere) {
//                 $results[] = [
//                     'id' => $notification->id,
//                     'error' => 1,
//                     'message' => 'Demande non trouvée.'
//                 ];
//                 continue;
//             }

//             $circuit = circuit_organe::where('label', $demande_recupere->direction_id)
//                 ->where('filliale_id', $demande_recupere->filliale_id)
//                 ->with('users')
//                 ->first();

//             $organe = organe_validateur::where('label', $demande_recupere->type_demandes_id)
//                 ->where('filliale_id', $demande_recupere->filliale_id)
//                 ->with('types')
//                 ->get()
//                 ->pluck('types')
//                 ->flatten();

//             $circuit_organe = $organe->map(function($circuit_o) {
//                 return [
//                     'circuit' => $circuit_o->circuit,
//                 ];
//             });

//             if ($demande_recupere->type_demandes_id == 1) {
//                 $demande_recupere->payement = $request->payement;
//                 $demande_recupere->save();
//             }

//             if ($request->statut == 2 ) {
//                 $demande_recupere->statut = 2;
//                 $demande_recupere->save();

//                 demande::where('id', $notification->demande_id)->update([
//                     'motif' => $request->motif,
//                 ]);

//                 // 2. Mettre à jour toutes les notifications associées à cette demande
//                 notification_demande::where('demande_id', $demande_recupere->id)->update(['statut' => 2]); // Mettre toutes les notifications à "rejeté"

//                 $statut = $this->miseAJourStatutDemande($demande_recupere);

//                 // Appeler le contrôleur pour l'envoi d'un email de rejet
//                 (new SendEmailController())->NotificationValidate($statut, user::find($demande_recupere->user_id));

//                 // Sortir immédiatement pour éviter d'autres notifications
//                 return response()->json([
//                     'error' => 0,
//                     'message' => 'La demande a été rejetée avec succès.'
//                 ]);
//             }
//             if ($request->statut == 1) {
//                 // Mise à jour du statut et du motif lorsque statut = 1

//                 demande::where('id', $notification->demande_id)->update([
//                     'motif' => $request->motif,
//                 ]);
//             }

//             if ($request->statut == 1) {
//                 $demandesExistantes = demande::where('type_demandes_id', 3)
//                     ->where('direction_id', $demande_recupere->direction_id)
//                     ->with(['accords' => function ($query) {
//                         $query->where('statut', 1);
//                     }])
//                     ->get();

//                 if ($demande_recupere->type_demandes_id == 3) {
//                     // code...
//                     // Ajoutez cette ligne après la récupération de la direction de l'utilisateur
//                     $demandesExistantes = demande::where('type_demandes_id', 3)
//                         ->where('direction_id', $demande_recupere->direction_id)
//                         ->where(function ($query) use ($demande_recupere) {
//                             $query->whereBetween('date_depart', [$demande_recupere['date_depart'], $demande_recupere['date_fin']])
//                                 ->orWhereBetween('date_fin', [$demande_recupere['date_depart'], $demande_recupere['date_fin']])
//                                 ->orWhere(function ($query) use ($demande_recupere) {
//                                     $query->where('date_depart', '<=', $demande_recupere['date_depart'])
//                                     ->where('date_fin', '>=', $demande_recupere['date_fin']);
//                                 });
//                             })
//                             ->with(['accords' => function ($query) {
//                             $query->where('statut', 1);
//                         }])
//                         ->get();

//                     // Vérifier si $demandesExistantes est vide
//                     if ($demandesExistantes->isEmpty()) {

//                         foreach ($demandesExistantes as $demandeExistante) {
//                             // dd($demandeExistante->accords->count());
//                             // if ($demandesExistante->accords->where('statut', 1)->count() > 0) {
//                             //     return response()->json([
//                             //         'error' => 1,
//                             //         'message' => 'Vous ne pouvez pas valider cette demande car la période est déjà occupée par un autre congé.'
//                             //     ]);
//                             // }
//                         }
//                     }
//                 }

//                 // if (count($demandesExistantes) > 0 && $demande_recupere->type_demandes_id == 3)
//                 // {
//                 //     foreach ($demandesExistantes as $demandesExistante) {
//                 //         $dateDebutNouvelle = Carbon::parse($demande_recupere->date_depart);
//                 //         $dateFinNouvelle = Carbon::parse($demande_recupere->date_fin);

//                 //         $dateDebutExistante = Carbon::parse($demandesExistante->date_depart);
//                 //         $dateFinExistante = Carbon::parse($demandesExistante->date_fin);

//                 //         // Vérification des chevauchements de dates
//                 //         $datesChevauchent = $this->chevauchementDates($dateDebutNouvelle, $dateFinNouvelle, $dateDebutExistante, $dateFinExistante);

//                 //         if ($datesChevauchent) {
//                 //             // Vérifie si la demande existante a des accords validés
//                 //             if ($demandesExistante->accords->where('statut', 1)->count() > 0) {
//                 //                 return response()->json([
//                 //                     'error' => 1,
//                 //                     'message' => 'Vous ne pouvez pas valider cette demande car la période est déjà occupée par un autre congé validé.'
//                 //                 ]);
//                 //             }
//                 //         }
//                 //     }
//                 // }

//                 $total_notification = notification_demande::where('demande_id', $demande_recupere->id)->count();

//                 $total_circuit_user = $circuit_organe
//                     ->filter(function ($value) {
//                         // Vérifiez si le circuit et la relation users existent
//                         return isset($value['circuit']) && !empty($value['circuit']->users);
//                     })
//                     ->sum(function ($value) {
//                         // Comptez les utilisateurs uniquement si la relation users est valide
//                         return $value['circuit']->users->count();
//                     });

//                 $total_user_validateur = ($circuit ? $circuit->users->count() : 0) + $total_circuit_user;

//                 if ($total_notification == $total_user_validateur) {
//                     $demande_recupere->statut = 1;
//                     $demande_recupere->save();

//                     demande::where('id', $notification->demande_id)->update([
//                         'motif' => $request->motif,
//                     ]);

//                     $statut = $this->miseAJourStatutDemande($demande_recupere);

//                     (new SendEmailController())->NotificationValidate($statut, user::find($demande_recupere->user_id));

//                     if ($demande_recupere->type_demandes_id == 3) {
//                         $userProfile = UserProfile::find($demande_recupere->user_id);
//                         $nouveauNombreDeJours = $userProfile->jour_de_conger - $demande_recupere->nombre_de_jours;
//                         $userProfile->update(['jour_de_conger' => $nouveauNombreDeJours]);
//                     }
//                 }

//                 $notification->update(['statut' => $request->statut]);

//                 $this->notification($notification->demande_id);

//                 $results[] = [
//                     'id' => $notification->id,
//                     'error' => 0,
//                     'message' => 'La demande a bien été validée.'
//                 ];
//             }
//         }

//         $errors = array_filter($results, function ($result) {
//             return $result['error'] === 1;
//         });

//         if (!empty($errors)) {
//             return response()->json($errors[0]);
//         } else {
//             return response()->json([
//                 'error' => 0,
//                 'message' => 'Toutes les demandes ont été validées avec succès.'
//             ]);
//         }
//     }

//     public function autonotifcationupdate($id)
//     {

//         // dd($demande_recupere);

//         // Récupération de la demande
//         $demande_recupere = demande::where('id', $id)->first();

//         // Récupération du circuit de la demande
//         $circuit = circuit_organe::where('label', $demande_recupere->direction_id)->where('filliale_id', $demande_recupere->filliale_id)->with('users')->first();

//         // Récupération de l'organe de la demande
//         $organe = organe_validateur::where('label', $demande_recupere->type_demandes_id)->where('filliale_id', $demande_recupere->filliale_id)->with('types')->get()->pluck('types')->flatten();

//         $organe_id = organe_validateur::where('label', $demande_recupere->type_demandes_id)->with('types')->first()->label;

//         $circuit_organe = $organe->map(function($circuit_o, $key)
//         {
//             return [
//                 'circuit' => $circuit_o->circuit,
//             ];
//         });
//         if ($demande_recupere->type_demandes_id == 3) {
//             // code...
//             // Ajoutez cette ligne après la récupération de la direction de l'utilisateur
//             $demandesExistantes = demande::where('type_demandes_id', 3)
//                 ->where('direction_id', $demande_recupere->direction_id)
//                 ->where(function ($query) use ($demande_recupere) {
//                     $query->whereBetween('date_depart', [$demande_recupere['date_depart'], $demande_recupere['date_fin']])
//                         ->orWhereBetween('date_fin', [$demande_recupere['date_depart'], $demande_recupere['date_fin']])
//                         ->orWhere(function ($query) use ($demande_recupere) {
//                             $query->where('date_depart', '<=', $demande_recupere['date_depart'])
//                                 ->where('date_fin', '>=', $demande_recupere['date_fin']);
//                         });
//                 })
//                 ->with(['accords' => function ($query) {
//                     $query->where('statut', 1);
//                 }])
//                 ->get();

//             // Vérifier si $demandesExistantes est vide
//             if ($demandesExistantes->isEmpty()) {

//                 foreach ($demandesExistantes as $demandeExistante) {
//                     // dd($demandeExistante->accords->count());
//                     // if ($demandesExistante->accords->where('statut', 1)->count() > 0) {
//                     //     return response()->json([
//                     //         'error' => 1,
//                     //         'message' => 'Vous ne pouvez pas valider cette demande car la période est déjà occupée par un autre congé.'
//                     //     ]);
//                     // }
//                 }
//             }
//         }


//         // dd($demande_recupere);
//         $total_notification = notification_demande::where('demande_id', $demande_recupere->id)->count();

//         $total_circuit_user = $circuit_organe
//             ->filter(function ($value) {
//                 // Vérifiez si le circuit et la relation users existent
//                 return isset($value['circuit']) && !empty($value['circuit']->users);
//             })
//             ->sum(function ($value) {
//                 // Comptez les utilisateurs uniquement si la relation users est valide
//                 return $value['circuit']->users->count();
//             });
//         // dd($total_notification, $total_circuit_user, $circuit->users->count());

//         $total_user_validateur = $circuit->users->count() + $total_circuit_user;


//         if($total_notification === $total_user_validateur)
//         {


//             $demande_recupere->statut = 1;
//             unset($demande_recupere['text_statut']);

//             $demande_recupere->save();

//             $statut = $this->miseAJourStatutDemande($demande_recupere);

//             (new SendEmailController())->NotificationValidate($statut, user::find($demande_recupere->user_id));
//             if ($demande_recupere->type_demandes_id == 3 && $demande_recupere->statut == 1)
//             {
//                 $userProfile = UserProfile::find($demande_recupere->user_id);

//                 // Calculer le nouveau nombre de jours de congé
//                 $nouveauNombreDeJours = $userProfile->jour_de_conger - $demande_recupere->nombre_de_jours;

//                 // Mettre à jour la colonne jour_de_conger du modèle UserProfile
//                 $userProfile->update(['jour_de_conger' => $nouveauNombreDeJours]);
//             }
//         }

//         // Ajouter le résultat à $results
//         $results[] = [
//             'id' => $demande_recupere->id,
//             'error' => 0, // ou le statut d'erreur approprié
//             'message' => 'La demande a bien été validée.'
//         ];
//     }

//     public function analyseshow(Request $request, $notification)
//     {
//         //
//         $notificationsArray = explode(',', $notification);

//         $notif_demandes = notification_demande::whereIn('id', $notificationsArray)->get();

//         $ids_demande_notif = $notif_demandes->pluck('demande_id');

//         $ids_notif = $notif_demandes->pluck('id');

//         // dd($request, is_array($notificationsArray),
//         // $notificationsArray = explode(',', $notification));

//         $demandes = Demande::whereIn('id', $ids_demande_notif)->get();

//         $ids_demande = $demandes->pluck('id');

//         $types_demandeid = $demandes->pluck('type_demandes_id');

//         // dd($ids_demande_notif, $notificationsArray, $demandes, $ids_demande, $types_demandeid);

//         return view('modals.analyse_demande',
//         [
//             'id'=>$ids_notif,
//             'demandes'=>$types_demandeid,
//         ]);
//     }

//     public function notification($demande_enregistre_id)
//     {
//         // Étape 1 : Récupérer la demande
//         $demande_recupere = demande::find($demande_enregistre_id);

//         if (!$demande_recupere) {
//             Log::error("Demande introuvable avec l'ID : $demande_enregistre_id");
//             return;
//         }

//         // Étape 2 : Récupérer le circuit de validation
//         $circuit = circuit_organe::where('label', $demande_recupere->direction_id)
//             ->where('filliale_id', $demande_recupere->filliale_id)
//             ->with('users')
//             ->first();

//         // Étape 3 : Récupérer les organes de validation
//         $organe = organe_validateur::where('label', $demande_recupere->type_demandes_id)
//             ->where('filliale_id', $demande_recupere->filliale_id)
//             ->with('types')
//             ->get()
//             ->pluck('types')
//             ->flatten();

//         $circuit_organe = $organe->map(function ($circuit_o) {
//             return [
//                 'circuit' => $circuit_o->circuit,
//             ];
//         });

//         $usersorgane = [];

//         // Étape 4 : Vérifier les notifications existantes
//         $nb_notif_user = notification_demande::where('demande_id', $demande_recupere->id)->count();

//         if ($demande_recupere->statut == 0) {
//             // Ajout des utilisateurs du circuit principal
//             if ($circuit && !empty($circuit->users)) {
//                 foreach ($circuit->users as $circuit_user_valideur) {
//                     $usersorgane[] = [$circuit->label, $circuit_user_valideur];
//                 }
//             }

//             // Ajout des utilisateurs des organes de validation
//             foreach ($circuit_organe as $circuit_valideur) {
//                 if (isset($circuit_valideur['circuit']) && !empty($circuit_valideur['circuit']->users)) {
//                     foreach ($circuit_valideur['circuit']->users as $circuit_organe_user_valideur) {
//                         $usersorgane[] = [$circuit_valideur['circuit']->label, $circuit_organe_user_valideur];
//                     }
//                 }
//             }

//             // Retirer les utilisateurs déjà notifiés
//             if ($nb_notif_user > 0) {
//                 $usersorgane = array_slice($usersorgane, $nb_notif_user);
//             }

//             $i = 0;
//             $nombre_utilisateur_notif = count($usersorgane);

//             // Notification des utilisateurs
//             foreach ($usersorgane as $key => $direction_user) {
//                 Log::info('Notification envoyée : ' . $key . ' ' . $direction_user[1]->user->name);

//                 $userId = $direction_user[1]->user->id;

//                 $notificationData = [
//                     'demande_id' => $demande_recupere->id,
//                     'statut' => $demande_recupere->statut,
//                     'user_id' => $userId,
//                     'circuit_id' => $direction_user[0],
//                     'order' => $direction_user[1]->order,
//                 ];

//                 notification_demande::create($notificationData);

//                 // Appeler la méthode pour envoyer un e-mail à l'utilisateur
//                 (new SendEmailController())->NotificationMail($demande_recupere, $direction_user[1]->user);


//                 // Si l'utilisateur n'est pas en auto-validation, sortir
//                 return;

//                 $i++;
//             }

//             // Étape 5 : Appeler autonotifcationupdate si toutes les notifications sont envoyées
//             if ($i == $nombre_utilisateur_notif) {
//                 $this->autonotifcationupdate($demande_recupere->id);
//             }
//         }
//     }

//     private function miseAJourStatutDemande($demande)
//     {
//         if ($demande->statut == 0) {
//             $demande['text_statut'] = 'En attente';
//         } elseif ($demande->statut == 1) {
//             $demande['text_statut'] = 'Validée';
//         } else {
//             $demande['text_statut'] = 'Rejetée';
//         }

//         return $demande;
//     }

//     /**
//      * Remove the specified resource from storage.
//      */
//     public function destroy(demande $id)
//     {
//         //
//         $id->delete();
//     }
// }

// <?php

// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Client;

class DemandeController extends Controller
{
    public function index()
    {
        $demandes = Demande::orderBy('motif', 'asc')->get();
        return response()->json($demandes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'montant_demande' => 'integer|nullable',
            'motif_permi' => 'string|nullable',
            'motif' => 'string|nullable',
            'detail' => 'string|nullable',
            'payement' => 'integer|nullable',
            'lieu_travail' => 'string|nullable',
            'heure_debut' => 'string|nullable',
            'heure_fin' => 'string|nullable',
            'date_depart' => 'date|nullable',
            'date_fin' => 'date|nullable',
            'type' => 'string|nullable',
            'nombre_de_jours'=>'integer|nullable',
            'user_id'=>'integer|nullable',
            'procces_valide_result'=>'json|nullable',
            'direction_id'=>'integer|nullable',
            'filliale_id'=>'integer|nullable',
            'type_demandes_id'=>'integer|nullable'
        ]);

        $dem = demande::create([
            'montant_demande' => $validated['montant_demande'] ?? null,
            'motif_permi' => $validated['motif_permi'] ?? null,
            'motif' => $validated['motif'] ?? null,
            'detail' => $validated['detail'] ?? null,
            'payement' => $validated['payement'] ?? null,
            'lieu_travail' => $validated['lieu_travail'] ?? null,
            'heure_debut' => $validated['heure_debut'] ?? null,
            'heure_fin' => $validated['heure_fin'] ?? null,
            'date_depart' => $validated['date_depart'] ?? null,
            'date_fin' => $validated['date_fin'] ?? null,
            'type' => $validated['type'] ?? null,
            'nombre_de_jours' => $validated['nombre_de_jours'] ?? null,
            'user_id' => 1,
            'procces_valide_result' => $validated['procces_valide_result'] ?? null,
            'direction_id' => $validated['direction_id'] ?? null,
            'filliale_id' => $validated['filliale_id'] ?? null,
            'type_demandes_id' => $validated['type_demandes_id'] ?? null,
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
                    'nom' => 'justificatif_demande_' . $dem->id.'_'.now(),
                    'chemin_doc' => $fileData['filePath'],
                    'user_id' => 1,
                    'demande_id' => $dem->id,
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

            $demande_recupere = demande::find($notification->demande_id);
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

                demande::where('id', $notification->demande_id)->update([
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

                demande::where('id', $notification->demande_id)->update([
                    'motif' => $request->motif,
                ]);
            }

            if ($request->statut == 1) {
                $demandesExistantes = demande::where('type_demandes_id', 3)
                    ->where('direction_id', $demande_recupere->direction_id)
                    ->with(['accords' => function ($query) {
                        $query->where('statut', 1);
                    }])
                    ->get();

                if ($demande_recupere->type_demandes_id == 3) {
                    // code...
                    // Ajoutez cette ligne après la récupération de la direction de l'utilisateur
                    $demandesExistantes = demande::where('type_demandes_id', 3)
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

                    demande::where('id', $notification->demande_id)->update([
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
        $demande_recupere = demande::where('id', $id)->first();

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
            $demandesExistantes = demande::where('type_demandes_id', 3)
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

        $demandes = Demande::whereIn('id', $ids_demande_notif)->get();

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
        $demande_recupere = demande::find($demande_enregistre_id);

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

    public function update(Request $request, $Demande)
    {
        $demande = Demande::findOrFail($Demande);

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
                    'nom' => 'justificatif_demande_' . $demande->id.'_'.now(),
                    'chemin_doc' => $fileData['filePath'],
                    'user_id' => 1,
                    'demande_id' => $demande->id,
                ]);
            }

        }

        // dd($dem_enregistre);
        //! envoie du mail de confirmation
            # code...

        // dd($dem->id);
        // envoie de notification pour validation
        // $this->notification($demande->id);

        $demande->update($validated);

        return response()->json(['message' => 'Demande mis à jour avec succès']);
    }

    public function destroy(demande $id)
    {
        $id->delete();

        return response()->json(['message' => 'Demande supprimé avec succès']);
    }
}
