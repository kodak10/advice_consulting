<?php

use App\Http\Controllers\Administration\ClientController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('frontend.pages.index');
});

Route::get('/dashboard', function () {
    return view('administration.pages.index');
});

Route::get('/devis/create', function () {
    return view('administration.pages.devis.create');
});

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::resource('clients', ClientController::class);
});