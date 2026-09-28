<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ReclamationController;
use App\Http\Controllers\Api\StatistiqueController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ProfilController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Welcome'));

Route::get('/login', fn () => Inertia::render('Auth/Login'))->name('login');
Route::get('/register', fn () => Inertia::render('Auth/Register'));

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [StatistiqueController::class, 'dashboard']);

    // Profil (tous rôles)
    Route::get('/profil', [ProfilController::class, 'show']);
    Route::put('/profil', [ProfilController::class, 'update']);
    Route::put('/profil/mot-de-passe', [ProfilController::class, 'updatePassword']);

    // Client
    Route::get('/reclamations/creer', [ReclamationController::class, 'creerForm']);
    Route::post('/reclamations', [ReclamationController::class, 'store']);
    Route::get('/mes-reclamations', [ReclamationController::class, 'mesReclamations']);
    Route::get('/mes-reclamations/{reclamation}', [ReclamationController::class, 'showClient']);
    Route::get('/mes-reclamations/{reclamation}/pdf', [ReclamationController::class, 'exporterPdf']);
    Route::get('/mes-notifications', [ReclamationController::class, 'mesNotifications']);

    // Agent
    Route::middleware('role:agent')->group(function () {
        Route::get('/agent/reclamations', [ReclamationController::class, 'pourAgent']);
        Route::patch('/agent/reclamations/{reclamation}/statut', [ReclamationController::class, 'changerStatut']);
    });

    // Admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/agents', [AdminController::class, 'indexAgents']);
        Route::get('/agents/creer', [AdminController::class, 'creerAgentForm']);
        Route::post('/agents', [AdminController::class, 'storeAgent']);
        Route::get('/agents/{agent}/modifier', [AdminController::class, 'editAgentForm']);
        Route::put('/agents/{agent}', [AdminController::class, 'updateAgent']);

        Route::get('/clients', [AdminController::class, 'indexClients']);
        Route::get('/clients/{client}', [AdminController::class, 'showClient']);

        Route::get('/services', [AdminController::class, 'indexServices']);
        Route::post('/services', [AdminController::class, 'storeService']);
        Route::put('/services/{service}', [AdminController::class, 'updateService']);
        Route::delete('/services/{service}', [AdminController::class, 'destroyService']);

        Route::get('/types', [AdminController::class, 'indexTypes']);
        Route::post('/types', [AdminController::class, 'storeType']);
        Route::put('/types/{type}', [AdminController::class, 'updateType']);
        Route::delete('/types/{type}', [AdminController::class, 'destroyType']);
    });
});