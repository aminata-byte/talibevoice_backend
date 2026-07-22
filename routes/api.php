<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Public\DaaraPublicController;
use App\Http\Controllers\Public\DonPublicController;
use App\Http\Controllers\Public\PartenairePublicController;
use App\Http\Controllers\Public\ContactPublicController;
use App\Http\Controllers\Admin\DaaraController;
use App\Http\Controllers\Admin\TalibeController;
use App\Http\Controllers\Admin\BesoinController;
use App\Http\Controllers\Admin\DonController;
use App\Http\Controllers\Admin\FormationController;
use App\Http\Controllers\Admin\InsertionController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PartenaireController;
use App\Http\Controllers\Admin\RapportController;
use App\Http\Controllers\Admin\RedistributionController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\UtilisateurController;
use App\Http\Controllers\Admin\MissionController as AdminMissionController;
use App\Http\Controllers\Admin\ObjectifController as AdminObjectifController;
use App\Http\Controllers\Agent\MissionController as AgentMissionController;
use App\Http\Controllers\Agent\RecensementController;
use App\Http\Controllers\Agent\ObjectifController as AgentObjectifController;

// ============================================
// ROUTES PUBLIQUES
// ============================================

// Auth
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

// Daaras publiques
Route::get('/daaras', [DaaraPublicController::class, 'index']);
Route::get('/daaras/{id}', [DaaraPublicController::class, 'show']);
Route::get('/daaras/{id}/besoins', [DaaraPublicController::class, 'besoins']);
Route::get('/stats', [DaaraPublicController::class, 'stats']);

// Dons publics
Route::post('/dons', [DonPublicController::class, 'store']);
Route::get('/dons/stats', [DonPublicController::class, 'stats']);
Route::get('/dons/statut/{reference}', [DonPublicController::class, 'statut']);

// Webhooks / notifications de paiement (Wave, Orange Money)
Route::post('/paiements/wave/webhook', [DonPublicController::class, 'waveWebhook']);
Route::post('/paiements/orange/notification', [DonPublicController::class, 'orangeNotification'])
    ->name('paiement.orange.notification');

// Partenaires publics
Route::post('/partenaires/candidature', [PartenairePublicController::class, 'candidature']);
Route::post('/partenaires/login', [PartenairePublicController::class, 'login']);
Route::post('/partenaires/recuperer-code', [PartenairePublicController::class, 'recupererCode']);

// Contact
Route::post('/contact', [ContactPublicController::class, 'store']);

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
  Route::put('/auth/me', [AuthController::class, 'updateMe']);
  Route::put('/auth/password', [AuthController::class, 'changePassword']);

  // ============================================
  // ROUTES ADMIN
  // ============================================
  Route::prefix('admin')->group(function () {

    // Daaras
    Route::get('/daaras', [DaaraController::class, 'index']);
    Route::post('/daaras', [DaaraController::class, 'store']);
    Route::get('/daaras/{id}', [DaaraController::class, 'show']);
    Route::put('/daaras/{id}', [DaaraController::class, 'update']);
    Route::delete('/daaras/{id}', [DaaraController::class, 'destroy']);
    Route::post('/daaras/{id}/valider', [DaaraController::class, 'valider']);

    // Talibés
    Route::get('/talibes', [TalibeController::class, 'index']);
    Route::get('/talibes/{id}', [TalibeController::class, 'show']);
    Route::delete('/talibes/{id}', [TalibeController::class, 'destroy']);

    // Besoins
    Route::get('/besoins', [BesoinController::class, 'index']);
    Route::get('/besoins/{id}', [BesoinController::class, 'show']);
    Route::post('/besoins/{id}/resoudre', [BesoinController::class, 'resoudre']);
    Route::delete('/besoins/{id}', [BesoinController::class, 'destroy']);

    // Dons
    Route::get('/dons', [DonController::class, 'index']);
    Route::get('/dons/{id}', [DonController::class, 'show']);
    Route::post('/dons/{id}/valider', [DonController::class, 'valider']);
    Route::post('/dons/{id}/rejeter', [DonController::class, 'rejeter']);

    // Redistributions
    Route::get('/redistributions', [RedistributionController::class, 'index']);
    Route::post('/redistributions', [RedistributionController::class, 'store']);
    Route::post('/redistributions/{id}/valider', [RedistributionController::class, 'valider']);

    // Messages de contact
    Route::get('/contacts', [AdminContactController::class, 'index']);
    Route::post('/contacts/{id}/lu', [AdminContactController::class, 'marquerLu']);
    Route::delete('/contacts/{id}', [AdminContactController::class, 'destroy']);

    // Formations
    Route::get('/formations', [FormationController::class, 'index']);
    Route::get('/formations/{id}', [FormationController::class, 'show']);
    Route::post('/formations/{id}/valider', [FormationController::class, 'valider']);
    Route::post('/formations/{id}/activer', [FormationController::class, 'activer']);
    Route::post('/formations/{id}/desactiver', [FormationController::class, 'desactiver']);
    Route::post('/formations/{id}/inscrire', [FormationController::class, 'inscrireTalibe']);
    Route::delete('/formations/{id}', [FormationController::class, 'destroy']);

    // Insertions
    Route::get('/insertions', [InsertionController::class, 'index']);
    Route::get('/insertions/{id}', [InsertionController::class, 'show']);
    Route::post('/insertions/{id}/valider', [InsertionController::class, 'valider']);
    Route::post('/insertions/{id}/cloturer', [InsertionController::class, 'cloturer']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);

    // Partenaires
    Route::get('/partenaires', [PartenaireController::class, 'index']);
    Route::get('/partenaires/{id}', [PartenaireController::class, 'show']);
    Route::post('/partenaires/{id}/valider', [PartenaireController::class, 'valider']);
    Route::post('/partenaires/{id}/rejeter', [PartenaireController::class, 'rejeter']);
    Route::delete('/partenaires/{id}', [PartenaireController::class, 'destroy']);

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

    // Missions
    Route::get('/missions', [AdminMissionController::class, 'index']);
    Route::get('/missions/{id}', [AdminMissionController::class, 'show']);
    Route::post('/missions', [AdminMissionController::class, 'store']);
    Route::post('/missions/{id}/assigner', [AdminMissionController::class, 'assignerAgent']);

    // Objectifs
    Route::get('/objectifs', [AdminObjectifController::class, 'index']);
    Route::post('/objectifs', [AdminObjectifController::class, 'store']);
    Route::put('/objectifs/{id}', [AdminObjectifController::class, 'update']);
    Route::delete('/objectifs/{id}', [AdminObjectifController::class, 'destroy']);
    Route::get('/objectifs/agent/{agentId}', [AdminObjectifController::class, 'parAgent']);
  });

  // ============================================
  // ROUTES AGENT
  // ============================================
  Route::prefix('agent')->group(function () {

    // Talibés
    Route::get('/talibes', [RecensementController::class, 'getTalibes']);
    Route::post('/talibes', [RecensementController::class, 'storeTalibe']);
    Route::get('/talibes/{id}', [RecensementController::class, 'showTalibe']);
    Route::put('/talibes/{id}', [RecensementController::class, 'updateTalibe']);
    Route::post('/talibes/{id}/document', [RecensementController::class, 'uploadDocument']);

    // Daaras
    Route::get('/daaras', [RecensementController::class, 'getDaaras']);
    Route::post('/daaras', [RecensementController::class, 'storeDaara']);

    // Besoins
    Route::post('/besoins', [RecensementController::class, 'storeBesoin']);

    // Missions
    Route::get('/missions', [AgentMissionController::class, 'index']);
    Route::get('/missions/{id}', [AgentMissionController::class, 'show']);
    Route::post('/missions/{id}/accepter', [AgentMissionController::class, 'accepter']);
    Route::post('/missions/{id}/cloturer', [AgentMissionController::class, 'cloturer']);

    // Rapports
    Route::post('/rapports', [AgentMissionController::class, 'storeRapport']);
    Route::get('/rapports', [AgentMissionController::class, 'getRapports']);
    Route::put('/rapports/{id}', [AgentMissionController::class, 'updateRapport']);

    // Notifications
    Route::get('/notifications', [AgentMissionController::class, 'getNotifications']);
    Route::post('/notifications/{id}/lue', [AgentMissionController::class, 'marquerLue']);

    // Objectifs
    Route::get('/objectifs', [AgentObjectifController::class, 'mesObjectifs']);
  });
});
