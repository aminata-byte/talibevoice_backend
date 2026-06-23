<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DaaraController;
use App\Http\Controllers\Admin\TalibeController;
use App\Http\Controllers\Admin\BesoinController;
use App\Http\Controllers\Admin\DonController;
use App\Http\Controllers\Admin\RedistributionController;
use App\Http\Controllers\Admin\FormationController;
use App\Http\Controllers\Admin\InsertionController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\RapportController;
use App\Http\Controllers\Admin\UtilisateurController;
use App\Http\Controllers\Admin\PartenaireController;
use App\Http\Controllers\Agent\RecensementController;
use App\Http\Controllers\Agent\MissionController;
use App\Http\Controllers\Public\DaaraPublicController;
use App\Http\Controllers\Public\DonPublicController;
use App\Http\Controllers\Public\PartenaireController as PartenairePublicController;

// ============================================
// ROUTES PUBLIQUES
// ============================================

// Auth
Route::post('/auth/login', [AuthController::class, 'login']);

// Daaras publiques
Route::get('/daaras', [DaaraPublicController::class, 'index']);
Route::get('/daaras/{id}', [DaaraPublicController::class, 'show']);
Route::get('/daaras/{id}/besoins', [DaaraPublicController::class, 'besoins']);
Route::get('/stats', [DaaraPublicController::class, 'stats']);

// Dons publics
Route::post('/dons', [DonPublicController::class, 'store']);
Route::get('/dons/stats', [DonPublicController::class, 'stats']);

// Partenaires publics
Route::post('/partenaires/candidature', [PartenairePublicController::class, 'candidature']);
Route::post('/partenaires/login', [PartenairePublicController::class, 'login']);

// ============================================
// ROUTES PARTENAIRE (token partenaire)
// ============================================
Route::prefix('partenaires')->group(function () {
  Route::get('/profil', [PartenairePublicController::class, 'profil']);
  Route::put('/profil', [PartenairePublicController::class, 'updateProfil']);
  Route::get('/offres', [PartenairePublicController::class, 'offres']);
  Route::post('/offres', [PartenairePublicController::class, 'submitOffre']);
  Route::get('/talibes-inscrits', [PartenairePublicController::class, 'talibesInscrits']);
  Route::get('/impact', [PartenairePublicController::class, 'impact']);
});

// ============================================
// ROUTES PROTEGEES (Sanctum)
// ============================================
Route::middleware('auth:sanctum')->group(function () {

  // Auth
  Route::post('/auth/logout', [AuthController::class, 'logout']);
  Route::get('/auth/me', [AuthController::class, 'me']);

  // ============================================
  // ROUTES ADMIN
  // ============================================
  Route::middleware('admin')->prefix('admin')->group(function () {

    // Daaras
    Route::get('/daaras', [DaaraController::class, 'index']);
    Route::get('/daaras/{id}', [DaaraController::class, 'show']);
    Route::delete('/daaras/{id}', [DaaraController::class, 'destroy']);
    Route::post('/daaras/{id}/activer', [DaaraController::class, 'activer']);
    Route::post('/daaras/{id}/desactiver', [DaaraController::class, 'desactiver']);
    Route::post('/daaras/{id}/valider', [DaaraController::class, 'valider']);

    // Talibés
    Route::get('/talibes', [TalibeController::class, 'index']);
    Route::get('/talibes/{id}', [TalibeController::class, 'show']);
    Route::delete('/talibes/{id}', [TalibeController::class, 'destroy']);

    // Besoins
    Route::get('/besoins', [BesoinController::class, 'index']);
    Route::get('/besoins/{id}', [BesoinController::class, 'show']);
    Route::delete('/besoins/{id}', [BesoinController::class, 'destroy']);
    Route::post('/besoins/{id}/resoudre', [BesoinController::class, 'resoudre']);

    // Dons
    Route::get('/dons', [DonController::class, 'index']);
    Route::get('/dons/{id}', [DonController::class, 'show']);
    Route::post('/dons/{id}/valider', [DonController::class, 'valider']);
    Route::post('/dons/{id}/rejeter', [DonController::class, 'rejeter']);
    Route::get('/dons/stats', [DonController::class, 'stats']);

    // Redistributions
    Route::get('/redistributions', [RedistributionController::class, 'index']);
    Route::post('/redistributions', [RedistributionController::class, 'store']);
    Route::post('/redistributions/{id}/valider', [RedistributionController::class, 'valider']);

    // Partenaires
    Route::get('/partenaires', [PartenaireController::class, 'index']);
    Route::get('/partenaires/{id}', [PartenaireController::class, 'show']);
    Route::post('/partenaires/{id}/valider', [PartenaireController::class, 'valider']);
    Route::post('/partenaires/{id}/rejeter', [PartenaireController::class, 'rejeter']);
    Route::delete('/partenaires/{id}', [PartenaireController::class, 'destroy']);

    // Formations
    Route::get('/formations', [FormationController::class, 'index']);
    Route::get('/formations/{id}', [FormationController::class, 'show']);
    Route::post('/formations/{id}/valider', [FormationController::class, 'valider']);
    Route::post('/formations/{id}/activer', [FormationController::class, 'activer']);
    Route::post('/formations/{id}/desactiver', [FormationController::class, 'desactiver']);
    Route::post('/formations/{id}/inscrire-talibe', [FormationController::class, 'inscrireTalibe']);
    Route::delete('/formations/{id}', [FormationController::class, 'destroy']);

    // Insertions
    Route::get('/insertions', [InsertionController::class, 'index']);
    Route::get('/insertions/{id}', [InsertionController::class, 'show']);
    Route::post('/insertions/{id}/valider', [InsertionController::class, 'valider']);
    Route::post('/insertions/{id}/cloturer', [InsertionController::class, 'cloturer']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);

    // Rapports
    Route::get('/rapports', [RapportController::class, 'index']);
    Route::get('/rapports/{id}', [RapportController::class, 'show']);
    Route::post('/rapports/{id}/valider', [RapportController::class, 'valider']);

    // Utilisateurs
    Route::get('/utilisateurs', [UtilisateurController::class, 'index']);
    Route::post('/utilisateurs', [UtilisateurController::class, 'store']);
    Route::put('/utilisateurs/{id}', [UtilisateurController::class, 'update']);
    Route::delete('/utilisateurs/{id}', [UtilisateurController::class, 'destroy']);
    Route::post('/utilisateurs/{id}/bloquer', [UtilisateurController::class, 'bloquer']);
    Route::post('/utilisateurs/{id}/debloquer', [UtilisateurController::class, 'debloquer']);
  });

  // ============================================
  // ROUTES AGENT
  // ============================================
  Route::middleware('agent')->prefix('agent')->group(function () {

    // Recensement
    Route::post('/talibes', [RecensementController::class, 'storeTalibe']);
    Route::post('/daaras', [RecensementController::class, 'storeDaara']);
    Route::post('/besoins', [RecensementController::class, 'storeBesoin']);
    Route::get('/talibes', [RecensementController::class, 'getTalibes']);
    Route::put('/talibes/{id}', [RecensementController::class, 'updateTalibe']);
    Route::get('/daaras', [RecensementController::class, 'getDaaras']);
    Route::post('/talibes/{id}/document', [RecensementController::class, 'uploadDocument']);

    // Missions
    Route::get('/missions', [MissionController::class, 'index']);
    Route::get('/missions/{id}', [MissionController::class, 'show']);
    Route::post('/missions/{id}/accepter', [MissionController::class, 'accepter']);
    Route::post('/missions/{id}/cloturer', [MissionController::class, 'cloturer']);

    // Rapports
    Route::post('/rapports', [MissionController::class, 'storeRapport']);
    Route::get('/rapports', [MissionController::class, 'getRapports']);

    // Notifications
    Route::get('/notifications', [MissionController::class, 'getNotifications']);
  });
});
