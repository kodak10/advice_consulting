<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BanqueController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DesignationController;
use App\Http\Controllers\Api\DevisController;
use App\Http\Controllers\Api\ConfigurationController;
use App\Models\Pays;

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


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
