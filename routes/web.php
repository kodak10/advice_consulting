<?php

use App\Http\Controllers\Administration\AdminController;
use App\Http\Controllers\Administration\ClientController;
use App\Http\Controllers\Administration\DesignationsController;
use App\Http\Controllers\Administration\DevisController;
use App\Http\Controllers\Administration\FacturesController;
use App\Http\Controllers\Administration\MessagerieController;
use App\Http\Controllers\Administration\UsersController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('frontend.pages.index');
});


Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [AdminController::class, 'index']);


    Route::resource('clients', ClientController::class);
    Route::resource('devis', DevisController::class);
    Route::resource('factures', FacturesController::class);
    Route::resource('designations', DesignationsController::class);
    Route::resource('messagerie', MessagerieController::class);
    Route::resource('users', UsersController::class);
    Route::get('users/profile', [UsersController::class, 'profile'])->name('users.profile');

});