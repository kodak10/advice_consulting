<?php

use App\Http\Controllers\Administration\AdminController;
use App\Http\Controllers\Administration\ClientController;
use App\Http\Controllers\Administration\DesignationsController;
use App\Http\Controllers\Administration\DevisController;
use App\Http\Controllers\Administration\FacturesController;
use App\Http\Controllers\Administration\MessagerieController;
use App\Http\Controllers\Administration\UsersController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Mail;


Route::get('/', function () {
    return view('frontend.pages.index');
});

Route::get('/login', function () {
    return view('frontend.pages.login');
});
Route::get('/password-forgot', function () {
    return view('frontend.pages.forgot-password');
});



Route::middleware(['auth', 'verified','check.user.status'])->prefix('dashboard')->name('dashboard.')->group(function () {

    Route::get('/', [AdminController::class, 'index']);
    Route::resource('clients', ClientController::class);

    Route::resource('designations', DesignationsController::class);
    Route::resource('users', UsersController::class);
    Route::post('/users', [UsersController::class, 'storeUser'])->name('storeUser');
    Route::get('/users/{id}/disable', [UsersController::class, 'disable'])->name('disable');
   // Route::get('/users/{id}/profil', [UsersController::class, 'show'])->name('users.profil');
   // Route::get('/users/{id}/profil', [UsersController::class, 'show'])->name('users.show');

    Route::resource('devis', DevisController::class);
    Route::get('/client/{id}', [ClientController::class, 'getClientInfo']);

    
    Route::resource('factures', FacturesController::class);
    Route::resource('messagerie', MessagerieController::class);

    
});
// Auth::routes();

//Route::get('/dashboard', [AdminController::class, 'index'])->name('home');
Auth::routes(['verify' => true]);
