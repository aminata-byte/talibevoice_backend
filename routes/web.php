<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\DonPublicController;

Route::get('/', function () {
    return view('welcome');
});

// Page de paiement simulée (mode démo, utilisée quand aucune clé API
// Wave/Orange Money n'est configurée sur le serveur).
Route::get('/paiement/simuler', [DonPublicController::class, 'simuler'])
    ->name('paiement.simuler');
Route::post('/paiement/simuler/confirmer', [DonPublicController::class, 'confirmerSimulation'])
    ->name('paiement.simuler.confirmer');
