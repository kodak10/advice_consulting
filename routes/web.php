<?php

use App\Http\Controllers\Administration\AdminController;
use App\Http\Controllers\Administration\BanqueController;
use App\Http\Controllers\Administration\ClientController;
use App\Http\Controllers\Administration\DesignationsController;
use App\Http\Controllers\Administration\DevisController;
use App\Http\Controllers\Administration\FacturesController;
use App\Http\Controllers\Administration\MessagerieController;
use App\Http\Controllers\Administration\UsersController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('frontend.pages.index');
});


Route::middleware(['auth', 'verified','check.user.status'])->prefix('dashboard')->name('dashboard.')->group(function () {

    Route::get('/', [AdminController::class, 'index']);
    Route::resource('clients', ClientController::class);
    Route::resource('designations', DesignationsController::class);
    Route::resource('users', UsersController::class);
    Route::post('/users', [UsersController::class, 'storeUser'])->name('storeUser');
    Route::get('/users/{id}/disable', [UsersController::class, 'disable'])->name('disable');
    Route::get('/users/{id}/activate', [UsersController::class, 'activate'])->name('activate');
    Route::get('/profil', [UsersController::class, 'profile'])->name('profil');
    Route::put('/profil/update', [UsersController::class, 'updateProfileImage'])->name('profil.image');
    Route::put('/profil/reset-image', [UsersController::class, 'resetProfileImage'])->name('profil.resetImage');
    Route::put('/profile/update', [UsersController::class, 'updateInformation'])->name('profil.updateInformation');
    Route::post('/update-password', [UsersController::class, 'updatePassword'])->name('profil.updatePassword');


    Route::resource('devis', DevisController::class);
    Route::post('/devis/create', [DevisController::class, 'recap'])->name('devis.recap');
    Route::post('/devis/{id}/edit/recap', [DevisController::class, 'recapUpdate'])->name('devis.recapUpdate');
    Route::put('/devis/{id}/store-recap', [DevisController::class, 'storeRecap'])->name('devis.storeRecap');
    Route::get('/devis/{id}/validate', [DevisController::class, 'approuve'])->name('devis.validate');


    
    //Route::resource('factures', FacturesController::class);
    Route::get('/factures', [FacturesController::class, 'index'])->name('factures.index');

    Route::get('/factures/{id}/refuse', [FacturesController::class, 'refuse'])->name('factures.refuse');
    // Route::get('/factures/{id}/create', [FacturesController::class, 'create'])->name('factures.create');
    Route::get('/factures/create/{id}', [FacturesController::class, 'create'])->name('factures.create');
    Route::post('/factures/store', [FacturesController::class, 'store'])->name('factures.store');
    
    Route::resource('banques', BanqueController::class);

    Route::resource('messagerie', MessagerieController::class);

    
});

Auth::routes(['verify' => true]);


// Route::fallback(function () {
//     return view('administration.pages.maintenance');
// })->withoutMiddleware(['auth']);
