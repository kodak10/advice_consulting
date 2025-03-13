<?php

use App\Events\DevisCreated;
use App\Http\Controllers\Administration\AdminController;
use App\Http\Controllers\Administration\BanqueController;
use App\Http\Controllers\Administration\ClientController;
use App\Http\Controllers\Administration\DesignationsController;
use App\Http\Controllers\Administration\DevisController;
use App\Http\Controllers\Administration\FacturesController;
use App\Http\Controllers\Administration\MessagerieController;
use App\Http\Controllers\Administration\UsersController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('frontend.pages.index');
});


Route::middleware(['auth', 'verified','check.user.status'])->prefix('dashboard')->name('dashboard.')->group(function () {

    Route::match(['get', 'post'], '/', [AdminController::class, 'index']);



    Route::get('/factures/data', [AdminController::class, 'getFactures'])->name('factures.data');

    // Route pour marquer toutes les notifications comme lues
    Route::post('/notifications/mark-all-as-read', [AdminController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    // Route pour marquer une notification spécifique comme lue
    Route::post('/notifications/mark-as-read/{id}', [AdminController::class, 'markAsRead'])->name('notifications.mark-as-read');


    Route::resource('clients', ClientController::class);
    Route::resource('designations', DesignationsController::class);

    Route::resource('users', UsersController::class);
    Route::get('/users/export/csv', [UsersController::class, 'exportCsv'])->name('users.exportCsv');
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
    Route::get('/edit/{id}/edit/recap');
    Route::get('/devis/{id}/validate', [DevisController::class, 'approuve'])->name('devis.validate');
    Route::get('/devis/download/{id}', [DevisController::class, 'download'])->name('devis.download');
    Route::get('/devis/export/csv', [DevisController::class, 'exportCsv'])->name('devis.exportCsv');
    Route::get('/devis/{id}/refuse', [DevisController::class, 'refuse'])->name('devis.refuse');

    
   // Route::get('/factures', [FacturesController::class, 'index'])
    Route::match(['get', 'post'], '/factures', [FacturesController::class, 'index'])->name('factures.index');

    Route::get('/factures/{id}/refuse', [FacturesController::class, 'refuse'])->name('factures.refuse');
    Route::get('/factures/create/{id}', [FacturesController::class, 'create'])->name('factures.create');
    Route::post('/factures/store', [FacturesController::class, 'store'])->name('factures.store');
    Route::get('/factures/download/{id}', [FacturesController::class, 'download'])->name('factures.download');
    Route::get('/factures/export/csv', [FacturesController::class, 'exportCsv'])->name('factures.exportCsv');
    Route::get('/factures/{id}/validate', [FacturesController::class, 'approuve'])->name('factures.validate');


    Route::resource('banques', BanqueController::class);


    Broadcast::routes();

});

Auth::routes(['verify' => true]);


Route::fallback(function () {
    return view('administration.pages.maintenance');
})->withoutMiddleware(['auth']);
