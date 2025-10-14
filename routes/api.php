<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BanqueController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DesignationController;
use App\Http\Controllers\Api\DevisController;
use App\Http\Controllers\Api\FactureController;
use App\Http\Controllers\Api\DeviseController;
use App\Http\Controllers\Api\ConfigurationController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Models\Pays;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Routes publiques (non protégées)
|--------------------------------------------------------------------------
*/

Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/forgot-password', [ApiAuthController::class, 'forgotPassword']);
Route::post('/reset-password', [ApiAuthController::class, 'resetPassword']);

/*
|--------------------------------------------------------------------------
| Routes protégées par Sanctum
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [ApiAuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('entreprise', [ConfigurationController::class, 'show']);
    Route::post('entreprise', [ConfigurationController::class, 'update']);

    Route::get('/roles', fn() => Role::all());
    Route::get('/pays', fn() => Pays::all());

    Route::resource('users', UserController::class);
    Route::resource('banques', BanqueController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('designations', DesignationController::class);
    Route::get('/categories', [DesignationController::class, 'getCategories']);

    Route::resource('devis', DevisController::class);
    Route::get('/devis/{id}/details', [DevisController::class, 'getDetails']);
    Route::get('/devis/{id}/pdf', [DevisController::class, 'getPdf']);
    Route::put('/devis/{id}/send-devis', [DevisController::class, 'sendDevis']);
    Route::put('/devis/{id}/refuse-devis', [DevisController::class, 'refuseDevis']);
    Route::get('/devis/suivi', [DevisController::class, 'suivi']);

    Route::get('/devises', [DeviseController::class, 'index']);
    Route::get('/taux-change', [DeviseController::class, 'getTauxChange']);

    Route::resource('factures', FactureController::class);
    Route::get('/factures/{id}/pdf', [FactureController::class, 'getPdf']);
    Route::post('/factures/{id}/paiements', [FactureController::class, 'ajouterPaiement']);
    Route::get('/factures/{id}/paiements', [FactureController::class, 'getHistoriquePaiements']);
    Route::put('/factures/{id}/validate', [FactureController::class, 'validateFacture']);
    Route::put('/factures/{id}/refuse-facture', [FactureController::class, 'refuseFacture']);
    Route::get('/factures/suivi', [FactureController::class, 'suivi']);
});
