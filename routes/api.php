<?php

use App\Http\Controllers\AbsencesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BanqueController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DesignationController;
use App\Http\Controllers\Api\DevisController;
use App\Http\Controllers\DeviseController;
use App\Http\Controllers\Api\ConfigurationController;
use App\Http\Controllers\BienEtServicesController;
use App\Models\Pays;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PictureController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\Api\TravelRequestController;
use App\Http\Controllers\CircuitOrganeController;
use App\Http\Controllers\CongerController;
use App\Http\Controllers\DemandePermissionsController;
use App\Http\Controllers\OrganeValidateurController;
use App\Http\Controllers\FillialeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\RoleController;
use App\Models\TravelRequest;
use App\Models\DemObjet;

Route::get('entreprise', [ConfigurationController::class, 'show']);
Route::post('entreprise', [ConfigurationController::class, 'update']);

Route::get('/roles', function() {
    return Spatie\Permission\Models\Role::all();
});

Route::get('/pays', function() {
    return Pays::all();
});

Route::resource('users', UserController::class);
Route::resource('banques', BanqueController::class);
Route::resource('clients', ClientController::class);
Route::resource('designations', DesignationController::class);
Route::resource('devis', DevisController::class);
Route::get('/devis/{id}/details', [DevisController::class, 'getDetails']);

Route::get('/devis/{id}/pdf', [DevisController::class, 'getPdf']);

Route::get('/devises', [DeviseController::class, 'index']);
Route::get('/taux-change', [DeviseController::class, 'getTauxChange']);

//route des demandes
Route::get('/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('change-password');
        Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');


        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/signature', [ProfileController::class, 'signature_update'])->name('signature.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/picture', [PictureController::class, 'store'])->name('picture.update');

        // route de pages de demande
        Route::prefix('demande')->group(function () {
            Route::resource('/',BienEtServicesController::class);
            Route::resource('/dem_objet',BienEtServicesController::class);
            Route::resource('/store', BienEtServicesController::class);
            Route::resource('/liste_dem_sg',BienEtServicesController::class);
            Route::resource('/edit',BienEtServicesController::class);
            Route::resource('/create',BienEtServicesController::class);
            Route::resource('/visualiser/view',BienEtServicesController::class);
            Route::patch('/update/{bien_et_services}',[BienEtServicesController::class, 'update']);
            Route::delete('/delete/{id}', [BienEtServicesController::class, 'destroy']);
            // Route::get('/demandes/filter', [DemandeController::class, 'filter'])->name('demandes.filter');
            // teste de l'envoie de mail de demande
            Route::get('/mail_renvoi/{demande}',[BienEtServicesController::class, 'renvoi_mail']);
        });
        // route de pages de demande Absence
        Route::prefix('absence')->group(function () {
            Route::resource('/absence',AbsencesController::class);
            Route::resource('/storeabsence', AbsencesController::class);
            Route::resource('/editabsence',AbsencesController::class);
            Route::resource('/createabsence',AbsencesController::class);
            Route::resource('/visualiser/views',AbsencesController::class);
            Route::patch('/updates/{Absence}',[AbsencesController::class, 'update']);
            Route::delete('/deletes/{id}', [AbsencesController::class, 'destroy']);
            // Route::get('/demandes/filter', [DemandeController::class, 'filter'])->name('demandes.filter');
            // teste de l'envoie de mail de demande
            Route::get('/mail_renvois/{demande}',[AbsencesController::class, 'renvoi_mail']);
        });
        // route de pages de demande Conges
        Route::prefix('congers')->group(function () {
            Route::resource('/conger',CongerController::class);
            Route::resource('/storeconger', CongerController::class);
            Route::resource('/editconger',CongerController::class);
            Route::resource('/createconger',CongerController::class);
            // Route::resource('/visualiser/views',CongerController::class);
            Route::patch('/update/conger/{conger}',[CongerController::class, 'update']);
            Route::delete('/delete/conger/{id}', [CongerController::class, 'destroy']);
            // Route::get('/demandes/filter', [DemandeController::class, 'filter'])->name('demandes.filter');
            // teste de l'envoie de mail de demande
            Route::get('/mail_renvoi/conger/{demande}',[CongerController::class, 'renvoi_mail']);
        });
        Route::prefix('permission')->group(function () {
            Route::resource('/permission',DemandePermissionsController::class);
            Route::resource('/storepermission', DemandePermissionsController::class);
            Route::resource('/editpermission',DemandePermissionsController::class);
            Route::resource('/createpermission',DemandePermissionsController::class);
            // Route::resource('/visualiser/views',CongerController::class);
            Route::patch('/updates/permission/{demandepermissions}',[DemandePermissionsController::class, 'update']);
            Route::delete('/deletes/permission/{id}', [DemandePermissionsController::class, 'destroy']);
            // Route::get('/demandes/filter', [DemandeController::class, 'filter'])->name('demandes.filter');
            // teste de l'envoie de mail de demande
            Route::get('/mail_renvoi/permission/{demande}',[DemandePermissionsController::class, 'renvoi_mail']);
        });
        Route::get('/calculer',[DemandeController::class, 'calculerDateFin']);

        // route pour les demandes de voyages
        Route::get('/travel',[TravelRequestController::class, 'index'])->name('travel');
        Route::post('/travel', [TravelRequestController::class, 'store'])->name('travel_request');
        Route::get('/travel/edit/{id}',[TravelRequestController::class, 'edit']);
        Route::get('/travel/imprimer/{id}',[TravelRequestController::class, 'imprime']);
        Route::get('/travelrequest/create',[TravelRequestController::class, 'create']);
        Route::get('/demande/visualiser/view/{id}',[TravelRequestController::class, 'visualiser']);
        Route::get('/travel/{id}', [TravelRequestController::class, 'show']);
        Route::put('/travel/{id}',[TravelRequestController::class, 'update'])->name("travelrequest.update");
        Route::delete('/travelrequest/delete/{id}', [TravelRequestController::class, 'destroy'])->name('travelrequest.destroy');
        Route::get('/travel/{id}/pdf', [TravelRequestController::class, 'getPdf']);
    // route de pages de tratement de demande
        Route::get('/traiter_demande/visualiser/view/{id}',[DemandeController::class, 'visualiser']);
        Route::get('/traiter_demande', function () {return view('/pages/traiter_demande');})->name('traiter_demande');
        Route::get('/traiter_demande/visualiser/view/{id}',[DemandeController::class, 'visualiser']);

        Route::get('/demande_traiter', [DemandeController::class, 'indexdemtraiter'])->name('demande_traiter');

        // pour definir l'accord d'une demande
        Route::get('/traiter_demande/analyse_demande/view/{notification}',[DemandeController::class, 'analyseshow'])->name("analyse_demande.show");
        Route::get('/demande_traiter/visualiser/view/{id}',[DemandeController::class, 'visualiser']);
        Route::patch('/analyse_demande_update/view/{notification}',[DemandeController::class, 'notificationupdate'])->name("analyse_demande.update");

        // route de pages de traitement des Mission
        // Route::get('/demande_traiter', [TravelRequestController::class, 'indexdemtraiter'])->name('demande_traiter');

        // pour definir l'accord d'une Mission
        Route::get('/traiter_demande/analyse_mission/view/{notification}',[TravelRequestController::class, 'analyseshow'])->name("analyse_mission.show");
        Route::patch('/analyse_mission_update/view/{notification}',[TravelRequestController::class, 'notificationupdate'])->name("analyse_mission.update");

        // route pour les filliales
        Route::post('/filliale', [FillialeController::class, 'store'])->name('traitement_demande');

        // circuit validateur route
        Route::get('/parametres/{vue}',[SettingController::class, 'menu_parametre'])->name('parametres.vers');
        Route::get('/parametres',[SettingController::class, 'index_parametre'])->name('parametres');


        // route de parametre objet
        Route::post('/objet_demande_g', [SettingController::class, 'store_objet_g'])->name('objet_demande_g');
        Route::get('/parametres/objet_demande_g/create',[SettingController::class, 'create']);
        Route::get('/parametres/objet_demande/objet_demande_g/edit/{titres}',[SettingController::class, 'edit_titre']);
        Route::patch('/objet_demande_g/{id}',[SettingController::class, 'update_titre'])->name("objet_demande_g.update");
        Route::delete('/parametres/objet_demande/objet_demande_g/delete/{id}', [SettingController::class, 'destroy_objet_g'])->name('objet_demande_g.destroy');

        Route::post('/objet_demande_sousobjet', [SettingController::class, 'store_sousobjet'])->name('objet_demande_sousobjet');
        Route::get('/parametres/objet_demande_sousobjet/create',[SettingController::class, 'create']);
        Route::get('/parametres/objet_demande/objet_demande_sousobjet/edit/{sousobjet}',[SettingController::class, 'edit_sous_objet']);
        Route::patch('/objet_demande_sousobjet/{id}',[SettingController::class, 'update_sous_objet'])->name("objet_demande_sousobjet.update");
        Route::delete('/parametres/objet_demande/objet_demande_sousobjet/delete/{id}', [SettingController::class, 'destroy_objet_sg'])->name('objet_demande_sousobjet.destroy');

        Route::post('/objet_demande_objet', [SettingController::class, 'store_objet'])->name('objet_demande_objet');
        Route::get('/parametres/objet_demande_objet/create',[SettingController::class, 'create']);
        Route::get('/parametres/objet_demande/objet_demande_objet/edit/{objet}',[SettingController::class, 'edit_objet']);
        Route::patch('/objet_demande_objet/{id}',[SettingController::class, 'update_objet'])->name("objet_demande_objet.update");
        Route::delete('/parametres/objet_demande/objet_demande_objet/delete/{id}', [SettingController::class, 'destroy_objet'])->name('objet_demande_objet.destroy');



        Route::get('/parametres/circuit/create',[CircuitOrganeController::class, 'create']);
        Route::get('/parametres/circuit_user/create',[CircuitOrganeController::class, 'create']);
        Route::post('/circuit_validateur', [CircuitOrganeController::class, 'store'])->name('circuit_validateur');
        Route::post('/circuit_user', [CircuitOrganeController::class, 'store_user_circuit'])->name('circuit_user');

        //route pour les modifications
        Route::get('/parametres/circuit/circuit_organe/edit/{Circuit_organe}',[CircuitOrganeController::class, 'edit']);

        Route::get('/parametres/circuit/circuit_organe_user/edit/{circuit_organe_user}',[CircuitOrganeController::class, 'edit_circuit_user']);

        //route pour l'enregistrement des modifications
        Route::patch('/circuit_validateur/{Circuit_organe}',[CircuitOrganeController::class, 'update'])->name("Circuit_validateur.update");

        Route::patch('/circuit_user/{Circuit_organe_user}',[CircuitOrganeController::class, 'update_circuit_user'])->name("Circuit_user.update");

        //route permettant les suppressions
        Route::delete('/parametres/circuit/circuit_organe/delete/{circuit_organe}', [CircuitOrganeController::class, 'destroy'])->name('circuit_organe.destroy');

        Route::delete('/parametres/circuit/circuit_organe_user/delete/{circuit_organe_user}', [CircuitOrganeController::class, 'destroy_user'])->name('circuit_organe_user.destroy');



        // route pour les organes validateurs
        Route::get('/organes',[CircuitOrganeController::class, 'index'])->name('organes');
        Route::get('/parametres/organe/create',[OrganeValidateurController::class, 'create']);
        Route::post('/organe', [OrganeValidateurController::class, 'store'])->name('organe_validateur');
        Route::post('/circuit_organe_validateur', [OrganeValidateurController::class, 'store_circuit'])->name('circuit_organe_validateur');

        //route pour modifier l'organe
        Route::get('/parametres/circuit/organe_validateur/edit/{organe}',[OrganeValidateurController::class, 'edit']);

        Route::get('/parametres/circuit/circuit_organe_validateur/edit/{organe_validateur}',[OrganeValidateurController::class, 'edit_organe']);

        //route pour l'enregistrement des organes modifications
        Route::patch('/organe_validateur/{organe}',[OrganeValidateurController::class, 'update'])->name("organe_validateur.update");

        Route::patch('/circuit_organe_validateur/{organe_validateur}',[OrganeValidateurController::class, 'update_organe'])->name("circuit_organe_validateur.update");

        // route pour supprimer des organes
        Route::delete('/parametres/circuit/organe_validateur/delete/{organe_validateur}', [OrganeValidateurController::class, 'destroy'])->name('organe_validateur.destroy');

        Route::delete('/parametres/circuit/circuit_organe_validateur/delete/{circuit_organe}', [OrganeValidateurController::class, 'destroy_circuit'])->name('circuit_organe_validateur.destroy');

        Route::get('/traceability', [DemandeController::class, 'indexhistorique'])->name('traceability.index');

        Route::get('/roles-permissions', [RoleController::class, 'manageRolesAndPermissions'])
        ->name('roles.permissions');
        Route::resource('roles', RoleController::class)->except(['show']);

        Route::get('/profiles',[ProfileController::class, 'index'])->name('profiles');

        Route::get('/profile/create',[ProfileController::class, 'create']);

        Route::post('/creer_utilisateur', [ProfileController::class, 'store'])->name('creer_utilisateur');
        Route::delete('/profiles/delete/{id}', [ProfileController::class, 'destroyprofile'])->name('profiles.destroy');
        Route::post('/profiles/activer/{id}', [ProfileController::class, 'statutactiver'])->name('profiles.statut');
        Route::post('/profiles/desactiver/{id}', [ProfileController::class, 'statutadesactiver'])->name('profiles.statuts');


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
